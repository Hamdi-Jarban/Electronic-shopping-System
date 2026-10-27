<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'defaultVariant', 'featureImage'])->latest()->paginate(20);
        return view('product.index', compact('products'));
    }

    public function create()
    {
        $brands = Brand::where('is_active', true)->select('id', 'name')->get();
        $categories = Category::where('is_active', true)->select('id', 'name')->get();
        $warehouses = Warehouse::where('is_active', true)->select('id', 'name')->get();
        return view('admin.products.create', compact('brands', 'categories', 'warehouses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'summary' => ['nullable', 'string', 'max:500'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['required', 'distinct', 'exists:categories,id'],
            'images' => ['required', 'array', 'min:1'],
            'images.*.file' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'images.*.is_featured' => ['sometimes', 'boolean'],
            'images.*.sort_order' => ['sometimes', 'integer', 'min:0'],
            'images.*.variant_index' => ['nullable', 'integer', 'min:0'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
            'variants.*.compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.attributes' => ['required', 'array'],
            'variants.*.warehouse_id' => ['required', 'exists:warehouses,id'],
            'variants.*.physical_qty' => ['required', 'integer', 'min:0'],
            'variants.*.low_stock_threshold' => ['sometimes', 'integer', 'min:0'],
        ]);

        foreach ($data['images'] as $image) {
            if (isset($image['variant_index']) && !array_key_exists($image['variant_index'], $data['variants'])) {
                return back()->withErrors(['images' => 'إحدى الصور مرتبطة بمتغير غير موجود.'])->withInput();
            }
        }

        $storedPaths = [];
        try {
            $images = [];
            foreach ($data['images'] as $image) {
                $path = $image['file']->store('products/images', 'public');
                $storedPaths[] = $path;
                $images[] = [
                    'image_path' => $path,
                    'is_featured' => (bool) ($image['is_featured'] ?? false),
                    'sort_order' => $image['sort_order'] ?? 0,
                    'variant_index' => $image['variant_index'] ?? null,
                ];
            }

            $product = DB::transaction(function () use ($data, $images) {
                $product = Product::create([
                    'brand_id' => $data['brand_id'] ?? null,
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(8)),
                    'description' => $data['description'] ?? null,
                    'summary' => $data['summary'] ?? null,
                    'is_active' => true,
                ]);
                $product->categories()->attach($data['category_ids']);

                $variantMap = [];
                foreach ($data['variants'] as $index => $variantData) {
                    $variant = $product->variants()->create([
                        'sku' => $this->uniqueSku($product->name, $data['brand_id'] ?? null, $variantData['attributes']),
                        'price' => $variantData['price'],
                        'compare_at_price' => $variantData['compare_at_price'] ?? null,
                        'attributes' => $variantData['attributes'],
                    ]);
                    $variantMap[$index] = $variant->id;
                    $variant->inventories()->create([
                        'warehouse_id' => $variantData['warehouse_id'],
                        'physical_qty' => $variantData['physical_qty'],
                        'reserved_qty' => 0,
                        'low_stock_threshold' => $variantData['low_stock_threshold'] ?? 5,
                    ]);
                }
                foreach ($images as $image) {
                    $product->images()->create([
                        'variant_id' => $image['variant_index'] === null ? null : $variantMap[$image['variant_index']],
                        'image_path' => $image['image_path'],
                        'is_featured' => $image['is_featured'],
                        'sort_order' => $image['sort_order'],
                    ]);
                }
                return $product->load('categories', 'variants.inventories', 'images');
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedPaths);
            throw $exception;
        }

        return response()->json(['success' => true, 'message' => 'تم إنشاء المنتج بنجاح.', 'data' => $product], 201);
    }

    private function uniqueSku(string $name, ?int $brandId, array $attributes): string
    {
        $brand = $brandId ? Brand::find($brandId)?->name : 'GEN';
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $brand ?? 'GEN'), 0, 3));
        $product = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
        $attrs = strtoupper(substr(preg_replace('/[^A-Za-z0-9-]/', '', implode('-', array_map('strval', $attributes))), 0, 40));
        do {
            $sku = $prefix.'-'.$product.'-'.($attrs !== '' ? $attrs.'-' : '').Str::upper(Str::random(8));
        } while (ProductVariant::where('sku', $sku)->exists());
        return $sku;
    }
}
