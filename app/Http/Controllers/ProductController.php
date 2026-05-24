<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = DB::table('product as p')
            ->leftJoin('brand as b', 'p.brand_id', '=', 'b.brand_id')
            ->leftJoin('product_variant as v', 'v.product_id', '=', 'p.product_id')
            ->leftJoin('product_category as pc', 'pc.product_id', '=', 'p.product_id')
            ->leftJoin('category as c', 'pc.category_id', '=', 'c.category_id')
            ->select(
                'p.product_id',
                'p.name as product_name',
                'p.description',
                'p.base_image_url',
                'p.is_active',
                'b.brand_id',
                'b.name as brand_name',
                'b.logo_url as brand_image',
                'v.variant_id',
                'v.SKU',
                'v.price as variant_price',
                'v.size_option',
                'v.color_option',
                'c.category_id',
                'c.name as category_name'
            )
            ->orderBy('p.product_id', 'desc')
            ->get();

        // تجميع البيانات
        $grouped = $products->groupBy('product_id')->map(function ($items) {
            $first = $items->first();
            return (object) [
                'product_id'      => $first->product_id,
                'product_name'    => $first->product_name,
                'description'     => $first->description,
                'base_image_url'  => $first->base_image_url,
                'is_active'       => $first->is_active,
                'brand_id'        => $first->brand_id,
                'brand_name'      => $first->brand_name,
                'brand_image'     => $first->brand_image,
                'variants'        => $items->pluck('variant_id')->filter()->unique()->values(),
                'variant_count'   => $items->pluck('variant_id')->filter()->unique()->count(),
                'min_price'       => $items->pluck('variant_price')->filter()->min(),
                'max_price'       => $items->pluck('variant_price')->filter()->max(),
                'sku_list'        => $items->pluck('SKU')->filter()->unique()->implode(', '),
                'categories'      => $items->pluck('category_name')->filter()->unique()->values(),
                'category_ids'    => $items->pluck('category_id')->filter()->unique()->values(),
                'total_stock'     => $items->pluck('variant_id')->filter()->unique()->count() * 10,
            ];
        })->values();

        // إحصائيات
        $stats = [
            'total'    => $grouped->count(),
            'active'   => $grouped->where('is_active', 1)->count(),
            'stock'    => $grouped->sum('total_stock'),
            'variants' => $grouped->sum('variant_count'),
        ];

        return view('product.index', [
            'products' => $grouped,
            'stats'    => $stats,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
