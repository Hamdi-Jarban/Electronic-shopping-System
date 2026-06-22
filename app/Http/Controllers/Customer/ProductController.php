<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        return view('shop.index');
    }
    public function index()
    {
        $products = Product::where('is_active', true)
      ->with([
        'brand' => function ($query) {
          return $query->select('id', 'name');
        },
        'defaultVariant',
        'featureImage'
      ])
      ->withCount('reviews')
      ->withAvg('reviews', 'rating')->paginate(12);
    return view('shop.index', compact('products'));
    }


    /**
     * Display the specified resource.
     */
    public function showSingleProduct($slug)
    {
        
    $product = Product::query()->with(['brand:id,name,logo_url','variants','images'])
    ->where('slug',$slug)->where('is_active',true)->firstOrFail();
return view('shop.details',compact('product'));
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
