<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

// استدعاء الموديلات الجديدة
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * عرض قائمة المنتجات مع الفلترة المتقدمة
     */
    public function index(Request $request)
    {
        // 1. بناء الاستعلام مع العلاقات لجلب المنتجات وحساب الأسعار والمتغيرات
        $query = Product::with(['categories'])
            ->leftJoin('product_variants', 'products.product_id', '=', 'product_variants.product_id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.brand_id')
            ->select(
                'products.product_id',
                'products.name as product_name', // 💡 جلبنا 'name' وعملنا له Alias باسم 'product_name' ليتوافق مع الـ Blade
                'products.base_image_url',
                'products.is_active',
                'products.created_at',
                'brands.name as brand_name',
                DB::raw('COUNT(product_variants.variant_id) as variant_count'),
                DB::raw('MIN(product_variants.price) as min_price'),
                DB::raw('MAX(product_variants.price) as max_price')
            )
            ->groupBy(
                'products.product_id',
                'products.name', // 💡 نضع الاسم الفعلي هنا في الـ Group By
                'products.base_image_url',
                'products.is_active',
                'products.created_at',
                'brands.name'
            );
        // فلتر البحث (اسم، SKU، وصف)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('products.product_name', 'LIKE', "%{$search}%")
                    ->orWhere('products.description', 'LIKE', "%{$search}%")
                    ->orWhere('product_variants.SKU', 'LIKE', "%{$search}%");
            });
        }

        // فلتر القسم
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.category_id', $request->category_id);
            });
        }

        // فلتر العلامة التجارية
        if ($request->filled('brand_id')) {
            $query->where('products.brand_id', $request->brand_id);
        }

        // فلتر الحالة (نشط / غير نشط)
        if ($request->filled('is_active')) {
            $query->where('products.is_active', $request->is_active);
        }

        // فلتر المخزون (متوفر / غير متوفر)
        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'in_stock') {
                $query->where('product_variants.stock_quantity', '>', 0);
            } elseif ($request->stock_status == 'out_of_stock') {
                $query->where('product_variants.stock_quantity', '<=', 0);
            }
        }

        // فلتر نطاق السعر
        if ($request->filled('price_from')) {
            $query->where('product_variants.price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $query->where('product_variants.price', '<=', $request->price_to);
        }

        // الترتيب (Sort By)
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'oldest':
                    $query->orderBy('products.created_at', 'asc');
                    break;
                case 'price_asc':
                    $query->orderBy('min_price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('min_price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('products.product_name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('products.product_name', 'desc');
                    break;
                // الأحدث هو الافتراضي
                default:
                    $query->orderBy('products.created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('products.created_at', 'desc');
        }

        // 2. جلب المنتجات المفلوترة
        $products = $query->get();

        // 3. حساب الإحصائيات (الكرت العلوي في شاشتك)
        $stats = [
            'total'    => Product::count(),
            'active'   => Product::where('is_active', 1)->count(),
            'stock' => DB::table('product_variants')->sum('packaging'), // إجمالي قطع المخزون
            'variants' => DB::table('product_variants')->count(), // إجمالي عدد المتغيرات
        ];

        // 4. جلب القوائم المنسدلة لخيارات الفلترة
        $categories = Category::select('category_id', 'name')->get();
        $brands     = Brand::select('brand_id', 'name')->get();

        // 5. تمرير كل البيانات المجهزة إلى الـ View
        return view('product.index', compact('products', 'stats', 'categories', 'brands'));
    }
    /**
     * عرض صفحة إنشاء منتج جديد
     */
    public function create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        $suppliers = Supplier::all();
        $warehouses = Warehouse::all();

        return view('product.create', compact('brands', 'categories', 'suppliers', 'warehouses'));
    }

    /**
     * حفظ منتج جديد مع كافة ملحقاته (مخزن، موردين، أقسام، ومتغيرات)
     */
    public function store(Request $request)
    {
        // 1. التحقق من صحة البيانات (Validation) بناءً على حقول الـ Form الخاص بك
        $request->validate([
            'product_name'   => 'required|string|max:255',
            'brand_id'       => 'nullable|exists:brands,brand_id',
            'description'    => 'nullable|string',
            'base_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'      => 'required|boolean',
            'categories'     => 'nullable|array',
            'categories.*'   => 'exists:categories,category_id',

            // التحقق من مصفوفة المتغيرات
            'variants'       => 'required|array|min:1',
            'variants.*.SKU' => 'required|string|distinct|unique:product_variants,SKU',
            'variants.*.price' => 'required|numeric|min:0',

            // التحقق من مصفوفة الموردين والمخازن
            'suppliers'      => 'required|array|min:1',
            'inventory'      => 'required|array|min:1',
        ]);

        // 2. بدء الـ Transaction لتأمين عملية التخزين متعددة الجداول
        DB::beginTransaction();

        try {
            // 3. التعامل مع رفع الصورة الرئيسية للمنتج
            $imagePath = null;
            if ($request->hasFile('base_image_url')) {
                $imagePath = $request->file('base_image_url')->store('products', 'public');
            }

            // 4. حفظ المنتج الأساسي في جدول products
            $product = Product::create([
                'name'           => $request->product_name,
                'slug'           => Str::slug($request->product_name) . '-' . uniqid(),
                'description'    => $request->description,
                'base_image_url' => $imagePath ? '/storage/' . $imagePath : null,
                'is_active'      => $request->is_active,
                'brand_id'       => $request->brand_id,
            ]);

            // 5. ربط الأقسام في جدول كسر العلاقة (Many-to-Many)
            if ($request->has('categories')) {
                $product->categories()->attach($request->categories);
            }

            // 6. حفظ الموردين وسعر التوريد في جدول كسر العلاقة (Many-to-Many)
            foreach ($request->suppliers as $sup) {
                if (!empty($sup['supplier_id'])) {
                    $product->suppliers()->attach($sup['supplier_id'], [
                        'supply_price'    => $sup['supply_price'],
                        'lead_time_days' => $sup['lead_time_days'] ?? 0,
                        'minimum_order'   => $sup['minimum_order'] ?? 1,
                    ]);
                }
            }

            // 7. حفظ المتغيرات والمخزون التابع لها
            foreach ($request->variants as $index => $varData) {
                // حفظ المتغير أولاً في جدول product_variants لنحصل على المعرّف الخاص به
                $variant = $product->variants()->create([
                    'SKU'          => $varData['SKU'],
                    'price'        => $varData['price'],
                    'size_option'  => $varData['size_option'] ?? null,
                    'color_option' => $varData['color_option'] ?? null,
                    'packaging'    => $varData['packaging'] ?? null,
                    'weight_kg'    => $varData['weight_kg'] ?? null,
                ]);

                // ربط المخزون الأولي بالمتغير الذي تم إنشاؤه للتو (حسب الترتيب والـ Index)
                if (isset($request->inventory[$index])) {
                    $invData = $request->inventory[$index];
                    $variant->inventories()->create([
                        'warehouse_id'      => $invData['warehouse_id'],
                        'quantity_in_stock' => $invData['quantity'],
                        'reorder_level'     => $invData['reorder_level'] ?? 10,
                        'reorder_quantity'  => $invData['reorder_quantity'] ?? 50,
                    ]);
                }
            }

            // إذا وصلت العمليات هنا بنجاح تام، يتم تثبيت البيانات في MySQL
            DB::commit();

            return redirect()->route('product.index')->with('success', '📦 تم حفظ المنتج ومتغيراته ومخزونه بنجاح كفو!');
        } catch (\Exception $e) {
            // في حال حدوث أي خطأ، يتم التراجع عن كل شيء فوراً كأن شيئاً لم يكن
            DB::rollBack();

            Log::error('خطأ أثناء حفظ المنتج: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', '❌ عذراً، حدث خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }

    /**
     * جلب بيانات المنتج للتعديل (واجهة إرجاع JSON متطورة للـ Modals أو الـ Views)
     */

    public function edit($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'المنتج غير موجود'], 404);
        }

        // جلب العلاقات المرتبطة بالمنتج لبناء التبويبات المتعددة في المودال
        $variants           = $product->variants; // علاقة HasMany مع جدول المتغيرات
        $productCategories  = $product->categories()->pluck('categories.category_id')->toArray(); // مصفوفة الـ IDs المرتبطة
        $productSuppliers   = $product->suppliers()->select('suppliers.supplier_id', 'supply_price', 'lead_time_days', 'minimum_order')->get();

        // جلب البيانات العامة لتغذية القوائم المنسدلة داخل المودال
        $brands     = Brand::select('brand_id', 'name')->get();
        $categories = Category::select('category_id', 'name')->get();
        $suppliers  = Supplier::select('supplier_id',  'company_name')->get();

        return response()->json([
            'success'            => true,
            'product'            => $product,
            'brands'             => $brands,
            'categories'         => $categories,
            'variants'           => $variants,
            'product_categories' => $productCategories,
            'product_suppliers'  => $productSuppliers,
            'suppliers'          => $suppliers
        ]);
    }

    /**
     * تحديث بيانات المنتج
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'المنتج غير موجود'], 404);
        }

        // التثبت من البيانات (Validation)
        $request->validate([
            'product_name' => 'required|string|max:255',
            'brand_id'     => 'nullable|exists:brands,brand_id',
            'is_active'    => 'required|in:0,1',
            'base_image_url' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // 1. تحديث البيانات الأساسية
            $product->product_name = $request->product_name;
            $product->brand_id     = $request->brand_id;
            $product->is_active    = $request->is_active;
            $product->description  = $request->description;

            // معالجة رفع الصورة إذا رفعت صورة جديدة
            if ($request->hasFile('base_image_url')) {
                // كود حذف الصورة القديمة من الـ Storage إن وُجدت
                if ($product->base_image_url) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $product->base_image_url));
                }
                $path = $request->file('base_image_url')->store('products', 'public');
                $product->base_image_url = Storage::url($path);
            }
            $product->save();

            // 2. تحديث التصنيفات (Many-to-Many)
            // إذا لم يتم تحديد أي تصنيف، نقوم بإرسال مصفوفة فارغة لفك الارتباط
            $product->categories()->sync($request->input('categories', []));

            // 3. تحديث المتغيرات (HasMany)
            // نقوم بمسح المتغيرات القديمة أو تحديثها بناءً على معمارية التصميم لديك، والأسير هنا هو إعادة البناء أو المزامنة بالـ ID
            if ($request->has('variants')) {
                // استخراج الـ IDs المرسلة للحفاظ عليها وحذف ما عداها
                $keptVariantIds = collect($request->variants)->pluck('variant_id')->filter()->toArray();
                $product->variants()->whereNotIn('variant_id', $keptVariantIds)->delete();

                foreach ($request->variants as $variantData) {
                    $product->variants()->updateOrCreate(
                        ['variant_id' => $variantData['variant_id'] ?? null],
                        [
                            'SKU'          => $variantData['SKU'],
                            'price'        => $variantData['price'],
                            'size_option'  => $variantData['size_option'] ?? null,
                            'color_option' => $variantData['color_option'] ?? null,
                            'packaging'    => $variantData['packaging'] ?? null,
                            'weight_kg'    => $variantData['weight_kg'] ?? null,
                        ]
                    );
                }
            }

            // 4. تحديث الموردين (Many-to-Many مع بيانات إضافية pivot)
            $syncSuppliers = [];
            if ($request->has('suppliers')) {
                foreach ($request->suppliers as $supData) {
                    if (!empty($supData['supplier_id'])) {
                        $syncSuppliers[$supData['supplier_id']] = [
                            'supply_price'    => $supData['supply_price'],
                            'lead_time_days'  => $supData['lead_time_days'] ?? null,
                            'minimum_order'   => $supData['minimum_order'] ?? 1,
                        ];
                    }
                }
            }
            $product->suppliers()->sync($syncSuppliers);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'تم تحديث جميع بيانات المنتج بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()], 500);
        }
    }
    /**
     * حذف منتج نهائياً (يتكفل بالـ Cascading في قاعدة البيانات)
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'المنتج غير موجود أساساً'], 404);
        }

        try {
            DB::beginTransaction();

            // حذف علاقات الجداول الوسيطة والمتغيرات المعتمدة (تلقائياً إذا كنت قد وضعت Cascade On Delete في الـ Migration)
            $product->categories()->detach();
            $product->suppliers()->detach();
            $product->variants()->delete();

            // حذف الصورة من القرص الصلب لتوفير المساحة
            if ($product->base_image_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->base_image_url));
            }

            $product->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'تم حذف المنتج وكافة ملحقاته بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'فشل عملية الحذف: ' . $e->getMessage()], 500);
        }
    }
}
