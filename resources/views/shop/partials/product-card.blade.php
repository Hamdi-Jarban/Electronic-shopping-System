{{-- بطاقة المنتج - تستخدم في كل الأقسام --}}
<div class="product-card-modern fade-in-up">
    {{-- شريط الخصم (اختياري) --}}
    @if($showDiscount ?? false)
        <div class="product-badge discount-badge">-30%</div>
    @endif

    {{-- زر المفضلة --}}
    @auth
        <button class="wishlist-btn" onclick="toggleWishlist({{ $product->product_id }}, this)" title="أضف للمفضلة">
            <i class="bi bi-heart"></i>
        </button>
    @endauth

    {{-- صورة المنتج --}}
    <div class="product-image-wrapper">
        <a href="{{ route('shop.show', $product->product_id) }}">
            <img src="{{asset('storage/'.$product->base_image_url) ?? 'https://via.placeholder.com/400x400?text=' . urlencode($product->name) }}" 
                 alt="{{ $product->name }}" 
                 class="product-image"
                 loading="lazy">
        </a>

        {{-- أزرار hover --}}
        <div class="product-actions">
            @auth
                @php $firstVariant = $product->variants->first(); @endphp
                @if($firstVariant)
                    <button class="action-btn add-cart-btn" onclick="addToCart({{ $firstVariant->variant_id }}, event)" title="أضف للسلة">
                        <i class="bi bi-cart-plus"></i>
                    </button>
                @endif
            @endauth

            @if($showQuickView ?? false)
                <button class="action-btn quick-view-btn" onclick="openQuickView({{ $product->product_id }})" title="نظرة سريعة">
                    <i class="bi bi-eye"></i>
                </button>
            @endif

            <a href="{{ route('shop.show', $product->product_id) }}" class="action-btn details-btn" title="التفاصيل">
                <i class="bi bi-info-lg"></i>
            </a>
        </div>
    </div>

    {{-- معلومات المنتج --}}
    <div class="product-info">
        {{-- القسم --}}
        @if($product->categories->isNotEmpty())
            <span class="product-category">{{ $product->categories->first()->name }}</span>
        @endif

       {{-- التقييم --}}
<div class="product-rating">
    @php 
        $avgRating = 0;
        $reviewsCount = 0;
        try {
            $avgRating = $product->reviews()->avg('rating') ?? 0;
            $reviewsCount = $product->reviews()->count() ?? 0;
        } catch (\Exception $e) {
            // تجاهل الخطأ
        }
    @endphp
    @for($i = 1; $i <= 5; $i++)
        <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}"></i>
    @endfor
    <span class="rating-count">({{ $reviewsCount }})</span>
</div>
        </div>

        {{-- السعر --}}
        <div class="product-price-wrapper">
            <span class="current-price">{{ number_format($product->variants->min('price'), 2) }} ر.س</span>
            @if($showDiscount ?? false)
                <span class="old-price">{{ number_format($product->variants->min('price') * 1.4, 2) }} ر.س</span>
            @endif
        </div>

        {{-- زر الإضافة السريع (يظهر في الهاتف) --}}
        @auth
            @if($firstVariant)
                <button class="btn-mobile-add" onclick="addToCart({{ $firstVariant->variant_id }}, event)">
                    <i class="bi bi-cart-plus"></i> أضف للسلة
                </button>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn-mobile-add">
                <i class="bi bi-box-arrow-in-right"></i> سجل دخول للشراء
            </a>
        @endauth
    </div>
</div>