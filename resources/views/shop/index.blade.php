@extends('layouts.master')
@section('title', 'المنتجات | المتجر')
@section('content')
<main class="shop-page" dir="rtl">
    <div class="shop-container">
        <header class="shop-heading">
            <div><p class="shop-eyebrow">تسوّق بثقة</p><h1>منتجاتنا</h1><p>اكتشف المنتجات واختر ما يناسبك.</p></div>
            <a class="shop-cart-link" href="{{ route('cart.index') }}">عرض سلة التسوق</a>
        </header>
        @if (session('success'))<p class="shop-notice" role="status">{{ session('success') }}</p>@endif
        @if ($products->isEmpty())
            <div class="shop-empty"><h2>لا توجد منتجات متاحة حاليًا</h2><p>عد لاحقًا للاطلاع على المنتجات الجديدة.</p></div>
        @else
            <div class="products-grid">
                @foreach ($products as $product)
                    @php
                        $variant = $product->defaultVariant;
                        $price = $variant?->price;
                        $oldPrice = $variant?->compare_at_price;
                        $image = $product->featureImage;
                        $imageUrl = $image ? asset('storage/' . $image->image_path) : 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=640&h=640&fit=crop';
                        $discount = $oldPrice && $price && $oldPrice > $price ? round((($oldPrice - $price) / $oldPrice) * 100) : 0;
                    @endphp
                    <article class="product-card">
                        <a class="product-card__image" href="{{ route('products.show', $product->slug) }}" aria-label="تفاصيل {{ $product->name }}">
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy">
                            @if ($discount)<span class="product-discount">خصم {{ $discount }}٪</span>@endif
                        </a>
                        <div class="product-card__body">
                            <p class="product-card__brand">{{ $product->brand->name ?? 'متجرنا' }}</p>
                            <h2><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h2>
                            @if ($product->summary)<p class="product-card__summary">{{ \Illuminate\Support\Str::limit($product->summary, 90) }}</p>@endif
                            @if (($product->reviews_count ?? 0) > 0)<p class="product-card__rating" aria-label="تقييم {{ number_format($product->reviews_avg_rating ?? 0, 1) }} من 5">★ {{ number_format($product->reviews_avg_rating ?? 0, 1) }} <span>({{ $product->reviews_count }})</span></p>@endif
                            <div class="product-card__price">
                                @if ($price !== null)<strong>{{ number_format($price, 2) }} ر.س</strong>@else<strong>غير متاح</strong>@endif
                                @if ($oldPrice && $price && $oldPrice > $price)<del>{{ number_format($oldPrice, 2) }} ر.س</del>@endif
                            </div>
                            <div class="product-card__actions">
                                <a class="shop-button shop-button--secondary" href="{{ route('products.show', $product->slug) }}">عرض التفاصيل</a>
                                @if ($variant)
                                    <form class="add-to-cart-form" action="{{ route('cart.add') }}" method="POST" data-cart-form>
                                        @csrf<input type="hidden" name="variant_id" value="{{ $variant->id }}"><input type="hidden" name="quantity" value="1">
                                        <button class="shop-button shop-button--primary" type="submit">أضف للسلة</button>
                                        <p class="cart-feedback" data-cart-feedback role="status" aria-live="polite"></p>
                                    </form>
                                @else
                                    <button class="shop-button shop-button--disabled" type="button" disabled>غير متوفر</button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="shop-pagination">{{ $products->links() }}</div>
        @endif
    </div>
</main>
@endsection
