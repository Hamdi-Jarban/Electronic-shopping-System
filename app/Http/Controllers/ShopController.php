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
        if($request->filled('search'))
            {
                $req->where('name','like','%'.$request->search.'%');
            }
        if($request->filled('category'))
            {
                $req->whereHas('categories',function ($q)use($request){
                    $q->where('category_id',$request->category);
                });
            }
        $products = $req->paginate(12);
        $categories = Category::whereNull('parent_category_id')->with('children')->get();
        $brands = Brand::all();

        return view('shop.index', compact('products', 'categories', 'brands'));
    }
}
