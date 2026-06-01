<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    //
    public function index(Request $request)
    {
        $req = Product::with(['brand','variants','categories'])
        ->where('is_active',true);
        $product = $req->paginate(12);
        $categories = Category::whereNull('parent_category_id')->with('children')->get();
        $brands = Brand::all(12);

        return view('shop.index', compact('products', 'categories', 'brands'));
    }
}
