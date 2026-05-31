<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// ◄ أسطر الاستدعاء المفقودة التي تسبب الخطأ الرئيسي
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. تجهيز الاستعلام الأساسي مع الـ Eager Loading لمنع الـ N+1 Query
        $query = Product::with(['brand', 'categories', 'variants.inventory']);

        // 🔍 فلتر البحث الذكي (يبحث في اسم المنتج، أو SKU المتغير)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('variants', function ($vQ) use ($search) {
                        $vQ->where('sku', 'like', "%{$search}%");
                    });
            });
        }

        // 📂 فلتر الأقسام (يدعم جدول الربط المتقدم Many-to-Many)
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->get('category_id'));
            });
        }

        // 🏷️ فلتر العلامة التجارية
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->get('brand_id'));
        }

        // 📊 فلتر الحالة (نشط / غير نشط)
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        // 🟢🔴 فلتر حالة المخزون (متوفر / غير متوفر) مرتبط بالـ Variant والمخزون الجديد
        if ($request->filled('stock_status')) {
            if ($request->get('stock_status') === 'in_stock') {
                $query->whereHas('variants.inventory', function ($q) {
                    $q->where('quantity_in_stock', '>', 0);
                });
            } else {
                // منتج ليس لديه أي متغير بمخزون أكبر من صفر
                $query->whereDoesntHave('variants.inventory', function ($q) {
                    $q->where('quantity_in_stock', '>', 0);
                });
            }
        }

        // 💰 فلتر مدى السعر (يعتمد على أسعار المتغيرات المتاحة)
        if ($request->filled('price_from')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '>=', $request->get('price_from'));
            });
        }
        if ($request->filled('price_to')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '<=', $request->get('price_to'));
            });
        }

        // 🔥 ترتيب البيانات الاحترافي (Sorting)
        switch ($request->get('sort_by', 'newest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->withMin('variants', 'price')->orderBy('variants_min_price', 'asc');
                break;
            case 'price_desc':
                $query->withMax('variants', 'price')->orderBy('variants_max_price', 'desc');
                break;
            default:
                $query->latest();
        }

        // 📦 جلب المنتجات مع الترقيم التلقائي (Pagination) لحماية الأداء
        $products = $query->paginate(12)->withQueryString();

        // 📊 حساب الإحصائيات بدقة مذهلة وسرعة عبر استعلامات تجميعية خفيفة
        $stats = [
            'total'    => Product::count(),
            'active'   => Product::where('is_active', true)->count(),
            'stock'    => DB::table('inventories')->sum('quantity_in_stock'), // استعلام مباشر سريع جداً للتقرير
            'variants' => DB::table('product_variants')->count(),
        ];

        // جلب الفلاتر المساعدة للـ Dropdowns عبر الموديلات بشكل مباشر وأنظف
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('product.index', compact('products', 'stats', 'categories', 'brands'));
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            // . جلب المنتج الأساسي مع العلامة التجارية
            $product = DB::table('product as p')
                ->leftJoin('brand as b', 'p.brand_id', '=', 'b.brand_id')
                ->where('p.product_id', $id)
                ->select(
                    'p.product_id',
                    'p.name as product_name',
                    'p.description',
                    'p.base_image_url',
                    'p.is_active',
                    'p.brand_id',
                    'b.name as brand_name'
                )
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج غير موجود'
                ], 404);
            }

            // . جلب جميع المتغيرات
            $variants = DB::table('product_variant')
                ->where('product_id', $id)
                ->select(
                    'variant_id',
                    'SKU',
                    'size_option',
                    'color_option',
                    'packaging',
                    'price',
                    'weight_kg',
                    'image_url'
                )
                ->get();

            // . جلب التصنيفات الحالية للمنتج
            $productCategories = DB::table('product_category')
                ->where('product_id', $id)
                ->pluck('category_id')
                ->toArray();

            //  جلب الموردين الحاليين للمنتج
            $productSuppliers = DB::table('product_supplier')
                ->where('product_id', $id)
                ->select('supplier_id', 'supply_price', 'lead_time_days', 'minimum_order')
                ->get();

            //  جلب جميع العلامات التجارية
            $brands = DB::table('brand')
                ->select('brand_id', 'name')
                ->orderBy('name')
                ->get();

            // . جلب جميع التصنيفات
            $categories = DB::table('category')
                ->select('category_id', 'name', 'parent_category_id')
                ->orderBy('name')
                ->get();

            // . جلب جميع الموردين
            $suppliers = DB::table('supplier')
                ->select('supplier_id', 'company_name', 'contact_person')
                ->orderBy('company_name')
                ->get();

            // . إرجاع جميع البيانات
            return response()->json([
                'success' => true,
                'product' => $product,
                'variants' => $variants,
                'product_categories' => $productCategories,
                'product_suppliers' => $productSuppliers,
                'brands' => $brands,
                'categories' => $categories,
                'suppliers' => $suppliers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $brands = DB::table('brand')->orderBy('name')->get();
        $categories = DB::table('category')->orderBy('name')->get();
        $suppliers = DB::table('supplier')->orderBy('company_name')->get();
        $warehouses = DB::table('warehouse')->orderBy('name')->get();

        return view('product.create', compact('brands', 'categories', 'suppliers', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            // . جلب المنتج القديم للتدقيق
            $oldProduct = DB::table('product')->where('product_id', $id)->first();
            if (!$oldProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج غير موجود'
                ], 404);
            }

            // . تحديث بيانات المنتج الأساسية
            $productData = [
                'name' => $request->product_name,
                'description' => $request->description,
                'brand_id' => $request->brand_id ?: null,
                'is_active' => $request->is_active ?? 1,
            ];

            //  معالجة الصورة إذا تم رفعها
            if ($request->hasFile('base_image_url')) {
                $imagePath = $request->file('base_image_url')->store('products', 'public');
                $productData['base_image_url'] = '/storage/' . $imagePath;
            }

            DB::table('product')
                ->where('product_id', $id)
                ->update($productData);

            //  تحديث المتغيرات (حذف القديمة وإضافة الجديدة)
            if ($request->has('variants')) {
                // حذف المتغيرات القديمة
                $oldVariantIds = DB::table('product_variant')
                    ->where('product_id', $id)
                    ->pluck('variant_id')
                    ->toArray();

                $newVariantIds = [];

                foreach ($request->variants as $variantData) {
                    if (!empty($variantData['variant_id'])) {
                        // تحديث متغير موجود
                        DB::table('product_variant')
                            ->where('variant_id', $variantData['variant_id'])
                            ->where('product_id', $id)
                            ->update([
                                'SKU' => $variantData['SKU'],
                                'size_option' => $variantData['size_option'] ?? null,
                                'color_option' => $variantData['color_option'] ?? null,
                                'packaging' => $variantData['packaging'] ?? null,
                                'price' => $variantData['price'],
                                'weight_kg' => $variantData['weight_kg'] ?? null,
                            ]);
                        $newVariantIds[] = $variantData['variant_id'];
                    } else {
                        // إضافة متغير جديد
                        $newId = DB::table('product_variant')->insertGetId([
                            'product_id' => $id,
                            'SKU' => $variantData['SKU'],
                            'size_option' => $variantData['size_option'] ?? null,
                            'color_option' => $variantData['color_option'] ?? null,
                            'packaging' => $variantData['packaging'] ?? null,
                            'price' => $variantData['price'],
                            'weight_kg' => $variantData['weight_kg'] ?? null,
                        ]);
                        $newVariantIds[] = $newId;
                    }
                }

                // حذف المتغيرات التي تمت إزالتها
                $variantsToDelete = array_diff($oldVariantIds, $newVariantIds);
                if (!empty($variantsToDelete)) {
                    DB::table('product_variant')
                        ->whereIn('variant_id', $variantsToDelete)
                        ->delete();
                }
            }

            //. تحديث التصنيفات (حذف القديمة وإضافة الجديدة)
            if ($request->has('categories')) {
                DB::table('product_category')
                    ->where('product_id', $id)
                    ->delete();

                $categoriesData = [];
                foreach ($request->categories as $categoryId) {
                    $categoriesData[] = [
                        'product_id' => $id,
                        'category_id' => $categoryId
                    ];
                }

                if (!empty($categoriesData)) {
                    DB::table('product_category')->insert($categoriesData);
                }
            }

            // . تحديث الموردين (حذف القديم وإضافة الجديد)
            if ($request->has('suppliers')) {
                DB::table('product_supplier')
                    ->where('product_id', $id)
                    ->delete();

                $suppliersData = [];
                foreach ($request->suppliers as $supplierData) {
                    if (!empty($supplierData['supplier_id'])) {
                        $suppliersData[] = [
                            'product_id' => $id,
                            'supplier_id' => $supplierData['supplier_id'],
                            'supply_price' => $supplierData['supply_price'] ?? 0,
                            'lead_time_days' => $supplierData['lead_time_days'] ?? null,
                            'minimum_order' => $supplierData['minimum_order'] ?? 1,
                        ];
                    }
                }

                if (!empty($suppliersData)) {
                    DB::table('product_supplier')->insert($suppliersData);
                }
            }

            // . تسجيل التدقيق
            DB::table('product_audit')->insert([
                'product_id' => $id,
                'user_id' => auth()->id() ?? 1,
                'action' => 'UPDATE',
                'old_data_json' => json_encode($oldProduct),
                'new_data_json' => json_encode($productData),
                'changed_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ تم تحديث المنتج وجميع البيانات المرتبطة به بنجاح'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => '❌ فشل التحديث: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = DB::table('product')->where('product_id', $id)->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج غير موجود'
                ], 404);
            }

            // ببساطة: احذف المنتج فقط - وقاعدة البيانات ستتولى الباقي عبر CASCADE
            DB::table('product')->where('product_id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => '✅ تم حذف المنتج بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ فشل الحذف: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'brand_id' => 'nullable|integer|exists:brand,brand_id',
            'base_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'required|in:0,1',

            // المتغيرات
            'variants' => 'nullable|array',
            'variants.*.SKU' => 'required|string|max:100|distinct',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.size_option' => 'nullable|string|max:50',
            'variants.*.color_option' => 'nullable|string|max:50',
            'variants.*.packaging' => 'nullable|string|max:50',
            'variants.*.weight_kg' => 'nullable|numeric|min:0',

            // التصنيفات
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:category,category_id',

            // الموردين
            'suppliers' => 'nullable|array',
            'suppliers.*.supplier_id' => 'required|integer|exists:supplier,supplier_id',
            'suppliers.*.supply_price' => 'required|numeric|min:0',
            'suppliers.*.lead_time_days' => 'nullable|numeric|min:0',
            'suppliers.*.minimum_order' => 'nullable|numeric|min:1',

            // المخزون
            'inventory' => 'nullable|array',
            'inventory.*.warehouse_id' => 'required|integer|exists:warehouse,warehouse_id',
            'inventory.*.quantity' => 'required|numeric|min:0',
            'inventory.*.reorder_level' => 'nullable|numeric|min:0',
            'inventory.*.reorder_quantity' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // ========== رفع الصورة الرئيسية ==========
            $imagePath = null;
            if ($request->hasFile('base_image_url')) {
                $imagePath = '/storage/' . $request->file('base_image_url')->store('products', 'public');
            }

            // ========== إضافة المنتج الأساسي ==========
            $productId = DB::table('product')->insertGetId([
                'name' => $request->product_name,
                'description' => $request->description,
                'brand_id' => $request->brand_id ?: null,
                'base_image_url' => $imagePath,
                'is_active' => $request->is_active ?? 1,
            ]);

            // ========== إضافة المتغيرات ==========
            $variantIds = [];

            if ($request->has('variants') && is_array($request->variants)) {
                foreach ($request->variants as $index => $variant) {
                    if (empty($variant['SKU']) && empty($variant['price'])) {
                        continue;
                    }

                    $variantId = DB::table('product_variant')->insertGetId([
                        'product_id' => $productId,
                        'SKU' => $variant['SKU'],
                        'size_option' => $variant['size_option'] ?? null,
                        'color_option' => $variant['color_option'] ?? null,
                        'packaging' => $variant['packaging'] ?? null,
                        'price' => $variant['price'],
                        'weight_kg' => $variant['weight_kg'] ?? null,
                    ]);

                    $variantIds[] = $variantId;
                }
            }

            // ========== إضافة التصنيفات ==========
            if ($request->has('categories') && is_array($request->categories)) {
                $categoriesData = [];
                foreach ($request->categories as $categoryId) {
                    $categoriesData[] = [
                        'product_id' => $productId,
                        'category_id' => $categoryId,
                    ];
                }

                if (!empty($categoriesData)) {
                    DB::table('product_category')->insert($categoriesData);
                }
            }

            // ========== إضافة الموردين ==========
            if ($request->has('suppliers') && is_array($request->suppliers)) {
                $suppliersData = [];
                foreach ($request->suppliers as $supplier) {
                    if (empty($supplier['supplier_id']) || empty($supplier['supply_price'])) {
                        continue;
                    }

                    $suppliersData[] = [
                        'product_id' => $productId,
                        'supplier_id' => $supplier['supplier_id'],
                        'supply_price' => $supplier['supply_price'],
                        'lead_time_days' => $supplier['lead_time_days'] ?? null,
                        'minimum_order' => $supplier['minimum_order'] ?? 1,
                    ];
                }

                if (!empty($suppliersData)) {
                    DB::table('product_supplier')->insert($suppliersData);
                }
            }

            // ========== إضافة المخزون ==========
            if ($request->has('inventory') && is_array($request->inventory) && !empty($variantIds)) {
                $inventoryData = [];
                $firstVariantId = $variantIds[0];

                foreach ($request->inventory as $inv) {
                    if (empty($inv['warehouse_id'])) {
                        continue;
                    }

                    $inventoryData[] = [
                        'variant_id' => $firstVariantId,
                        'warehouse_id' => $inv['warehouse_id'],
                        'quantity_in_stock' => $inv['quantity'] ?? 0,
                        'reorder_level' => $inv['reorder_level'] ?? 10,
                        'reorder_quantity' => $inv['reorder_quantity'] ?? 50,
                        'last_updated' => now(),
                    ];
                }

                if (!empty($inventoryData)) {
                    DB::table('inventory')->insert($inventoryData);
                }
            }

            // ========== تسجيل التدقيق ==========
            DB::table('product_audit')->insert([
                'product_id' => $productId,
                'user_id' => auth()->id() ?? 1,
                'action' => 'INSERT',
                'old_data_json' => null,
                'new_data_json' => json_encode([
                    'product' => $request->only(['product_name', 'description', 'brand_id', 'is_active']),
                    'variants_count' => count($variantIds),
                    'categories_count' => count($request->categories ?? []),
                    'suppliers_count' => count($request->suppliers ?? []),
                ], JSON_UNESCAPED_UNICODE),
                'changed_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('product.index')
                ->with('success', '✅ تم إضافة المنتج "' . $request->product_name . '" بنجاح مع ' . count($variantIds) . ' متغيرات');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating product: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ فشل إضافة المنتج: ' . $e->getMessage());
        }
    }
}
