<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ShopController extends Controller
{

  public function index() {
    $categories = Category::select(['category_id', 'name'])
    ->has('products')
    ->with(['products' => function ($query) {
    $query->select(['product.product_id', 'product.name'])
    ->where('product.is_active', true)
    ->orderBy('product.created_at', 'desc')
    ->with(['variants' => function ($subQuery) {
    // جلب أعمدة المتغير المطلوبة دون وضع limit لمنع خطأ الـ row_number() الثاني
    $subQuery->select(['variant_id', 'product_id', 'price', 'packaging', 'image_url']);
    }]);
    }])->get();

    // 2. السحر المعماري المزدوج: تقطيع المنتجات والمتغيرات داخل الـ RAM بكفاءة تامة
    $categories->each(function ($category) {
    // أ) خذ أول 3 منتجات فقط للقسم
    $slicedProducts = $category->products->take(3)->map(function ($product) {
    // ب) لكل منتج، خذ المتغير الأول المتاح فقط واقطع الباقي لتوفر الذاكرة
    if ($product->variants->isNotEmpty()) {
    $product->setRelation('variants', collect([$product->variants->first()]));
    }
    return $product;
    });

    // ج) إعادة حقن المنتجات المحدثة للقسم
    $category->setRelation('products', $slicedProducts);
    });


    return view('customer.index', compact('categories'));
  }

  public shop() {
  }


  public function show($id) {
    $product = Product::with(['brand', 'categories', 'variants' => function($q) {
    $q->withSum('inventories as total_stock', 'quantity_in_stock');
    }])->findOrFail($id);

    return view('shop.show', compact('product'));
  }
}