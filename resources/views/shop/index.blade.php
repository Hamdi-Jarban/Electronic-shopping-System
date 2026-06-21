@extends('layouts.master')
@section('title', 'Home Page')

@section('content')


    <div class="products-grid">
        @foreach ($products as $product)
            @php
                $variant = $product->defaultVariant;
                $currentPrice = $variant ? $variant->price : 0;
                $oldPrice = $variant ? $variant->compare_at_price : null;
                $discountPercentage = 0;
                if ($oldPrice && $oldPrice > $currentPrice) {
                    $discountPercentage = round((($oldPrice - $currentPrice) / $oldPrice) * 100);
                }

                $imageSrc = $product->featureImage
                    ? asset('storage/' . $product->featureImage->image_path)
                    : 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=400&h=400&fit=crop';

                $isNew = $product->created_at && $product->created_at->greaterThanOrEqualTo(now()->subDays(7));
            @endphp
            <div class="product-card" data-product-id="{{ $product->id }}">
                <div class="product-card-image">
                    <img src="{{ $imageSrc }}" alt="{{ $product->name }}">
                    <div class="product-badges">
                        @if ($isNew)
                            <span class="badge badge-new">✨ جديد</span>
                        @endif
                        @if ($discountPercentage > 0)
                            <span class="badge badge-discount"
                                style="background-color: #ef4444; color: #ffffff; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">🔥
                                خصم {{ $discountPercentage }}%</span>
                        @endif
                    </div>
                    <button class="wishlist-btn" onclick="toggleWishlist({{ $product->id }}, this)">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                    </button>
                    <div class="product-actions">
                        <button class="btn-quick-view" onclick="openQuickView({{ $product->id }})">👁️ نظرة سريعة</button>
                        <button class="btn-cart-icon" onclick="addToCart({{ $variant->id ?? 0 }})"
                            title="إضافة سريعة للسلة">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1" />
                                <circle cx="20" cy="21" r="1" />
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="product-card-body">
                    <span class="product-brand">{{ $product->brand->name ?? 'ماركة عامة' }}</span>
                    <h3 class="product-name" title="{{ $product->name }}">
                        {{ Str::limit($product->name, 50, '...') }}
                    </h3>
                    <div class="product-rating">
                        <div class="stars" style="color: #f59e0b;">
                            @php
                                $rating = round($product->reviews_avg_rating ?? 0);
                            @endphp
                            {{ str_repeat('★', $rating) }}{{ str_repeat('☆', 5 - $rating) }}
                        </div>
                        <span class="rating-count">({{ $product->reviews_count ?? 0 }})</span>
                    </div>
                    <div class="product-price">
                        <span class="price-current">{{ number_format($currentPrice, 2) }} ر.س</span>
                        @if ($discountPercentage > 0)
                            <span class="price-old"
                                style="text-decoration: line-through; color: #9ca3af; font-size: 14px; margin-right: 8px;">
                                {{ number_format($oldPrice, 2) }} ر.س
                            </span>
                        @endif
                    </div>

                    <button class="btn-add-cart" data-variant-id="{{ $variant->id ?? 0 }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1" />
                            <circle cx="20" cy="21" r="1" />
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                        </svg>
                        أضف إلى السلة
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });
    </script>


@endsection
