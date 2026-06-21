<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;

class ShopController extends Controller
{
  /**
  * Display a listing of the resource.
  */
  public function home() {
  $cart = Cart
    return view('shop.index');
  }
  public function index() {
    $product = Product::query()->where('is_active',true)
    ->with(['brand:id,name, slug',
    'images'=>function($query){
    $query->select('id','product_id','image_path','is_featured')->where('is_featured',true)
    ->orderBy('sort_order','asc');
    },
    'variants'=>function($query){
    $query->select('id', 'product_id','price')->withSum('inventories as total_physical_stock','physical_qty');
    }])->whereHas('variants',function($query)
    {
    $query->whereHas('inventories',function($quantity)
    {
    $quantity->whereRaw('(physical_qty-reserved_qty)>0');
    });
    })
    ->latest()->paginate(15);
    return response()->json($product);
  }

  /**
  * Show the form for creating a new resource.
  */
  public function create() {
    $brands = Brand::query()->where('is_active',true)
    ->select('id','name')->get();
    $categories = Category::query()->where('is_active',true)
    ->select('id','name')->get();
    return view('admin.products.create', compact('brands', 'categories'));
    //
  }

  /**
  * Store a newly created resource in storage.
  */
  public function store(Request $request) {
    $validated = $request->validate([
    'name'                  => 'required|string|max:255',
    'description'           => 'nullable|string',
    'summary'               => 'nullable|string|max:500',
    'brand_id'              => 'nullable|exists:brands,id',
    'category_ids'          => 'required|array|min:1',
    'category_ids.*'        => 'exists:categories,id',

    'images'                => 'required|array|min:1',
    'images.*.file'         => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
    'images.*.is_featured'  => 'boolean',
    'images.*.sort_order'   => 'integer',
    'images.*.variant_index'=> 'nullable|integer', // لربط الصورة بمتغير معين إن وجد

    'variants'              => 'required|array|min:1',
    'variants.*.price'      => 'required|numeric|min:0',
    'variants.*.compare_at_price' => 'nullable|numeric|min:0',
    'variants.*.attributes' => 'required|array',
    'variants.*.warehouse_id' => 'required|exists:warehouses,id',
    'variants.*.physical_qty' => 'required|integer|min:0',
    'variants.*.low_stock_threshold' => 'integer|min:0',
    ]);
    $uploadedImagesData = [];
    foreach ($request->images as $index => $imgData) {
      if ($request->hasFile("images.{$index}.file")) {
        $file = $imgData['file'];
        $storedPath = $file->store('products/images', 'public');
        $uploadedImagesData[] = [
          'image_path' => $storedPath,
          // المسار النصي الناتج بداخل السيرفر
          'is_featured' => $imgData['is_featured'] ?? false,
          'sort_order' => $imgData['sort_order'] ?? 0,
          'variant_index' => $imgData['variant_index'] ?? null,
        ];
      }
    }

    return DB::transaction(function () use ($request, $uploadedImagesData) {

    $brandPrefix = 'GEN';
    if ($request->brand_id) {
    $brand = Brand::find($request->brand_id);
    $brandPrefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $brand->name), 0, 3));
    }
    $productPrefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $request->name), 0, 3));

    // أ. إنشاء المنتج الأساسي في قاعدة البيانات
    $product = Product::create([
    'brand_id'    => $request->brand_id,
    'name'        => $request->name,
    'slug'        => Str::slug($request->name) . '-' . uniqid(),
    'description' => $request->description,
    'summary'     => $request->summary,
    'is_active'   => true,
    ]);

    // ب. ربط المنتج بالتصنيفات (جدول category_product)
    $product->categories()->attach($request->category_ids);

    $variantMap = [];

    // ج. إنشاء المتغيرات والمخازن التابعة لها وتوليد الـ SKU تلقائياً
    foreach ($request->variants as $index => $variantData) {
    $attributeValues = array_values($variantData['attributes']);
    $attributesPrefix = strtoupper(implode('-', array_filter($attributeValues)));
    $attributesPrefix = preg_replace('/[^A-Za-z0-9\-]/', '', $attributesPrefix);

    $generatedSku = "{$brandPrefix}-{$productPrefix}-" . ($attributesPrefix ? "{$attributesPrefix}-" : "") . rand(1000, 9999);

    while (\App\Models\ProductVariant::where('sku', $generatedSku)->exists()) {
    $generatedSku = "{$brandPrefix}-{$productPrefix}-" . ($attributesPrefix ? "{$attributesPrefix}-" : "") . rand(1000, 9999);
    }

    // حفظ المتغير في جدول product_variants
    $variant = $product->variants()->create([
    'sku'              => $generatedSku,
    'price'            => $variantData['price'],
    'compare_at_price' => $variantData['compare_at_price'] ?? null,
    'attributes'       => $variantData['attributes'],
    ]);

    $variantMap[$index] = $variant->id;

    $variant->inventories()->create([
    'warehouse_id'        => $variantData['warehouse_id'],
    'physical_qty'        => $variantData['physical_qty'],
    'reserved_qty'        => 0,
    'low_stock_threshold' => $variantData['low_stock_threshold'] ?? 5,
    ]);
    }

    // د. ربط ألبوم الصور بالمنتج والمتغيرات باستخدام المسارات الفيزيائية التي رُفعت بنجاح
    foreach ($uploadedImagesData as $img) {
    $product->images()->create([
    'variant_id'  => $img['variant_index'] !== null ? $variantMap[$img['variant_index']] : null,
    'image_path'  => $img['image_path'], // السلسلة النصية الآمنة المرجعة من نظام الملفات
    'is_featured' => $img['is_featured'],
    'sort_order'  => $img['sort_order'],
    ]);
    }

    return response()->json([
    'success' => true,
    'message' => 'Product, files uploaded and relationships wired successfully!',
    'data'    => $product->load('categories', 'variants.inventories', 'images')
    ], 201);
    });
  }
  /**
  * Display the specified resource.
  */
  public function show(string $id) {
    //
  }

  /**
  * Show the form for editing the specified resource.
  */
  public function edit(string $id) {
    //
  }

  /**
  * Update the specified resource in storage.
  */
  public function update(Request $request, string $id) {
    //
  }

  /**
  * Remove the specified resource from storage.
  */
  public function destroy(string $id) {
    //
  }
}