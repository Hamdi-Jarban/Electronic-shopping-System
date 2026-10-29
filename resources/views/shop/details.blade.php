@extends('layouts.master')

@section('title', $product->name . ' | المتجر')

@section('content')
@php
    $defaultVariant = $product->variants->first();
    $featuredImage = $product->images->firstWhere('is_featured', true) ?? $product->images->first();
    $imageUrl = $featuredImage ? asset('storage/' . $featuredImage->image_path) : 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=900&h=900&fit=crop';
@endphp
<main class="shop-page" dir="rtl">
    <div class="shop-container">
        <nav class="shop-breadcrumb" aria-label="مسار التنقل">
            <a href="{{ route('index') }}">الرئيسية</a><span>/</span><a href="{{ route('index') }}">المنتجات</a><span>/</span><span>{{ $product->name }}</span>
        </nav>
        <section class="product-detail">
            <div class="product-detail__image-wrap">
                <img class="product-detail__image" src="{{ $imageUrl }}" alt="{{ $product->name }}">
                @if ($product->images->count() > 1)
                    <div class="product-detail__thumbnails" aria-label="صور المنتج">
                        @foreach ($product->images as $image)
                            <button type="button" class="product-thumbnail {{ $image->image_path === ($featuredImage?->image_path) ? 'is-active' : '' }}" data-image="{{ asset('storage/' . $image->image_path) }}" aria-label="عرض صورة المنتج"><img src="{{ asset('storage/' . $image->image_path) }}" alt=""></button>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="product-detail__content">
                <p class="product-detail__brand">{{ $product->brand->name ?? 'منتج من متجرنا' }}</p>
                <h1>{{ $product->name }}</h1>
                @if (($product->reviews_count ?? 0) > 0)
                    <p class="product-detail__rating"><span aria-label="التقييم">★</span> {{ number_format($product->reviews_avg_rating ?? 0, 1) }} <span>({{ $product->reviews_count }} تقييم)</span></p>
                @endif
                @if ($product->summary)
                    <p class="product-detail__summary">{{ $product->summary }}</p>
                @elseif ($product->description)
                    <p class="product-detail__summary">{{ $product->description }}</p>
                @endif
                @if ($product->variants->isNotEmpty())
                    <form class="add-to-cart-form product-detail__form" action="{{ route('cart.add') }}" method="POST" data-cart-form>
                        @csrf
                        <label class="shop-label" for="variant_id">الخيار</label>
                        <select id="variant_id" name="variant_id" class="shop-select" required>
                            @foreach ($product->variants as $variant)
                                @php($attributes = is_array($variant->attributes) ? $variant->attributes : [])
                                @php($variantLabel = collect($attributes)->map(fn ($value, $key) => $key . ': ' . $value)->implode('، '))
                                <option value="{{ $variant->id }}" data-price="{{ $variant->price }}" data-compare="{{ $variant->compare_at_price }}" {{ $defaultVariant?->id === $variant->id ? 'selected' : '' }}>{{ $variantLabel ?: ('خيار ' . $loop->iteration) }} — {{ number_format($variant->price, 2) }} ر.س</option>
                            @endforeach
                        </select>
                        <div class="product-detail__price" aria-live="polite"><strong id="selected-price">{{ number_format($defaultVariant->price, 2) }} ر.س</strong><del id="selected-compare-price" @if(!$defaultVariant->compare_at_price || $defaultVariant->compare_at_price <= $defaultVariant->price) hidden @endif>{{ number_format($defaultVariant->compare_at_price ?? 0, 2) }} ر.س</del></div>
                        <label class="shop-label" for="quantity">الكمية</label>
                        <input id="quantity" class="shop-quantity" type="number" name="quantity" value="1" min="1" max="99" inputmode="numeric" required>
                        <button class="shop-button shop-button--primary" type="submit">أضف إلى السلة</button>
                        <p class="cart-feedback" data-cart-feedback role="status" aria-live="polite"></p>
                    </form>
                @else
                    <p class="shop-unavailable" role="status">هذا المنتج غير متاح للشراء حاليًا.</p>
                @endif
                <div class="product-detail__meta"><span>SKU:</span> {{ $defaultVariant->sku ?? '—' }}</div>
                <a class="shop-back-link" href="{{ route('index') }}">العودة إلى المنتجات</a>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.product-thumbnail').forEach((button) => {
        button.addEventListener('click', () => {
            const image = document.querySelector('.product-detail__image');
            if (image) image.src = button.dataset.image;
            document.querySelectorAll('.product-thumbnail').forEach((item) => item.classList.remove('is-active'));
            button.classList.add('is-active');
        });
    });
    const variantSelect = document.getElementById('variant_id');
    variantSelect?.addEventListener('change', () => {
        const option = variantSelect.selectedOptions[0];
        const price = document.getElementById('selected-price');
        const compare = document.getElementById('selected-compare-price');
        if (price) price.textContent = `${Number(option.dataset.price).toFixed(2)} ر.س`;
        if (compare) {
            const compareValue = Number(option.dataset.compare || 0);
            compare.hidden = compareValue <= Number(option.dataset.price);
            compare.textContent = `${compareValue.toFixed(2)} ر.س`;
        }
    });
</script>
@endpush
