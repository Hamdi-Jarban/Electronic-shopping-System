@extends('layouts.customer')

@section('title', 'المنتجات')

@section('content')
<div class="row">
    <!-- فلاتر جانبية -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">بحث وتصفية</div>
            <div class="card-body">
                <form method="GET" action="{{ route('shop.index') }}">
                    <div class="mb-3">
                        <input type="text" name="search" class="form-control" placeholder="ابحث عن منتج..." value="{{ request('search') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">القسم</label>
                        <select name="category" class="form-select">
                            <option value="">الكل</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->category_id }}" {{ request('category') == $cat->category_id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @foreach($cat->children as $child)
                                    <option value="{{ $child->category_id }}" {{ request('category') == $child->category_id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;└ {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">العلامة التجارية</label>
                        <select name="brand" class="form-select">
                            <option value="">الكل</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->brand_id }}" {{ request('brand') == $brand->brand_id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ترتيب حسب</label>
                        <select name="sort" class="form-select">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">تطبيق</button>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary w-100 mt-2">مسح</a>
                </form>
            </div>
        </div>
    </div>

    <!-- قائمة المنتجات -->
    <div class="col-md-9">
        <h4 class="mb-3">
            @if(request('search'))
                نتائج البحث عن: "{{ request('search') }}"
            @else
                جميع المنتجات
            @endif
        </h4>

        <div class="row">
            @forelse($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card product-card h-100">
                        <img src="{{ $product->base_image_url ?? 'https://via.placeholder.com/300' }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted small">{{ $product->brand->name ?? '' }}</p>
                            <p class="card-text fw-bold text-primary">{{ number_format($product->min_price, 2) }} ر.س</p>
                            <a href="{{ route('shop.show', $product->product_id) }}" class="btn btn-outline-primary btn-sm">عرض التفاصيل</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">لا توجد منتجات متاحة حالياً</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection