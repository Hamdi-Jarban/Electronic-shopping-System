@extends('layouts.customer')

@section('title', 'المتجر - تسوق أفضل المنتجات')

@section('content')

{{-- ============================================ --}}
{{-- القسم 1: Hero Slider رئيسي --}}
{{-- ============================================ --}}
<section class="hero-slider-section">
    <div class="container">
        <div class="row g-3">
            {{-- السلايدر الرئيسي --}}
            <div class="col-lg-8">
                <div class="swiper hero-swiper">
                    <div class="swiper-wrapper">
                        {{-- سلايد 1 --}}
                        <div class="swiper-slide">
                            <div class="hero-slide" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <div class="row align-items-center h-100">
                                    <div class="col-md-7 p-5">
                                        <span class="slide-badge">🔥 عرض اليوم</span>
                                        <h2 class="slide-title">خصم حتى 50%</h2>
                                        <p class="slide-desc">على جميع المنتجات الإلكترونية</p>
                                        <a href="#products" class="btn btn-slide">تسوق الآن <i class="fas fa-arrow-left"></i></a>
                                    </div>
                                    <div class="col-md-5 text-center">
                                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/online-shopping-8791408-7233686.png" alt="Sale" class="slide-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- سلايد 2 --}}
                        <div class="swiper-slide">
                            <div class="hero-slide" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <div class="row align-items-center h-100">
                                    <div class="col-md-7 p-5">
                                        <span class="slide-badge">🎁 عرض خاص</span>
                                        <h2 class="slide-title">اشتري 2 واحصل على الثالث مجاناً</h2>
                                        <p class="slide-desc">على منتجات مختارة</p>
                                        <a href="#products" class="btn btn-slide">اكتشف العروض <i class="fas fa-arrow-left"></i></a>
                                    </div>
                                    <div class="col-md-5 text-center">
                                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/shopping-cart-8791407-7233685.png" alt="Offer" class="slide-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>

            {{-- البنرات الجانبية --}}
            <div class="col-lg-4">
                <div class="side-banners">
                    <div class="side-banner" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div>
                                <h5>خصم 30%</h5>
                                <p class="mb-0 small">منتجات العناية</p>
                            </div>
                            <img src="https://cdn-icons-png.flaticon.com/128/3081/3081559.png" alt="Care" width="60">
                        </div>
                    </div>
                    <div class="side-banner" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div>
                                <h5>توصيل مجاني</h5>
                                <p class="mb-0 small">للطلبات فوق 200 ر.س</p>
                            </div>
                            <img src="https://cdn-icons-png.flaticon.com/128/2769/2769339.png" alt="Delivery" width="60">
                        </div>
                    </div>
                    <div class="side-banner" style="background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div>
                                <h5>ضمان الجودة</h5>
                                <p class="mb-0 small">ضمان 100%</p>
                            </div>
                            <img src="https://cdn-icons-png.flaticon.com/128/4436/4436481.png" alt="Quality" width="60">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- القسم 2: شريط الفئات الدائري --}}
{{-- ============================================ --}}
<section class="categories-section">
    <div class="container">
        <div class="section-header-wrapper">
            <h3 class="section-heading">تسوق حسب القسم</h3>
            <a href="#" class="view-all">عرض الكل <i class="fas fa-arrow-left"></i></a>
        </div>
        <div class="swiper categories-swiper">
            <div class="swiper-wrapper">
                @php
                    $categoryIcons = ['📱', '💻', '👕', '🍔', '🧴', '🪑', '🎮', '📚', '⚽', '💄'];
                @endphp
                @foreach($categories->take(10) as $index => $category)
                    <div class="swiper-slide">
                        <a href="{{ route('shop.index', ['category' => $category->category_id]) }}" class="category-card">
                            <div class="category-icon-box" style="background: hsl({{ ($index * 36) % 360 }}, 70%, 90%);">
                                <span class="category-emoji">{{ $categoryIcons[$index] ?? '📦' }}</span>
                            </div>
                            <span class="category-name">{{ $category->name }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- القسم 3: عروض فلاش (مع عداد تنازلي) --}}
{{-- ============================================ --}}
<section class="flash-deals-section">
    <div class="container">
        <div class="flash-deals-header">
            <div class="d-flex align-items-center gap-3">
                <div class="flash-icon">⚡</div>
                <div>
                    <h3 class="flash-title">عروض البرق</h3>
                    <p class="flash-subtitle">ينتهي العرض قريباً</p>
                </div>
            </div>
            <div class="countdown-timer-wrapper">
                <span class="ends-in">ينتهي خلال:</span>
                <div class="countdown-digits">
                    <div class="digit-box"><span id="hours">12</span><small>ساعة</small></div>
                    <span class="digit-sep">:</span>
                    <div class="digit-box"><span id="minutes">30</span><small>دقيقة</small></div>
                    <span class="digit-sep">:</span>
                    <div class="digit-box"><span id="seconds">00</span><small>ثانية</small></div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($products->take(6) as $product)
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="deal-card">
                        <div class="deal-badge">-30%</div>
                        <a href="{{ route('shop.show', $product->product_id) }}">
                            <img src="{{asset('storage/'.$product->base_image_url) ?? 'https://picsum.photos/seed/'.$product->product_id.'/300/300' }}" alt="{{ $product->name }}" class="deal-img">
                        </a>
                        <div class="deal-info">
                            <h5 class="deal-name">{{ Str::limit($product->name, 20) }}</h5>
                            <div class="deal-price">
                                <span class="new-price">{{ number_format($product->variants->min('price') * 0.7, 2) }} ر.س</span>
                                <span class="old-price">{{ number_format($product->variants->min('price'), 2) }} ر.س</span>
                            </div>
                            <div class="deal-progress">
                                <div class="progress-bar" style="width: {{ rand(60, 95) }}%"></div>
                            </div>
                            <span class="sold-text">تم بيع {{ rand(10, 80) }} قطعة</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- القسم 4: المنتجات المميزة - 3 عواميد كبيرة --}}
{{-- ============================================ --}}
<section class="featured-products-section">
    <div class="container">
        <div class="section-header-wrapper">
            <h3 class="section-heading">منتجات مميزة</h3>
            <div class="section-tabs">
                <button class="tab-btn active" data-tab="all">الكل</button>
                <button class="tab-btn" data-tab="best">الأكثر مبيعاً</button>
                <button class="tab-btn" data-tab="new">الأحدث</button>
                <button class="tab-btn" data-tab="rated">الأعلى تقييماً</button>
            </div>
        </div>
        <div class="row" id="productsSection">
            @forelse($products->take(12) as $product)
                <div class="col-lg-3 col-md-4 col-6 mb-4">
                    <div class="product-card-final">
                        {{-- صورة المنتج --}}
                        <div class="product-img-container">
                            <a href="{{ route('shop.show', $product->product_id) }}">
                                <img src="{{ $product->base_image_url ?? 'https://picsum.photos/seed/'.$product->product_id.'/400/400' }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-img-main"
                                     loading="lazy">
                            </a>
                            {{-- أزرار سريعة --}}
                            <div class="product-quick-actions">
                                @auth
                                    @php $firstVariant = $product->variants->first(); @endphp
                                    @if($firstVariant)
                                        <button class="quick-btn quick-cart" onclick="addToCart({{ $firstVariant->variant_id }})" title="أضف للسلة">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    @endif
                                @endauth
                                <button class="quick-btn quick-heart" title="المفضلة">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="quick-btn quick-view" onclick="openQuickView({{ $product->product_id }})" title="نظرة سريعة">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            @if($product->variants->min('price') > 500)
                                <span class="product-tag tag-sale">تخفيض</span>
                            @endif
                        </div>
                        {{-- معلومات المنتج --}}
                        <div class="product-details">
                            <span class="product-brand-name">{{ $product->brand->name ?? 'عام' }}</span>
                            <h4 class="product-title">
                                <a href="{{ route('shop.show', $product->product_id) }}">{{ $product->name }}</a>
                            </h4>
                            <div class="product-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= rand(3, 5) ? 'active' : '' }}"></i>
                                @endfor
                                <span>({{ rand(10, 500) }})</span>
                            </div>
                            <div class="product-price-final">
                                <span class="price-now">{{ number_format($product->variants->min('price'), 2) }} ر.س</span>
                                @if($product->variants->min('price') > 300)
                                    <span class="price-before">{{ number_format($product->variants->min('price') * 1.3, 2) }} ر.س</span>
                                @endif
                            </div>
                            @auth
                                @if($firstVariant ?? false)
                                    <button class="btn-add-cart-full" onclick="addToCart({{ $firstVariant->variant_id }})">
                                        <i class="fas fa-cart-plus"></i> أضف للسلة
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-add-cart-full">
                                    <i class="fas fa-sign-in-alt"></i> سجل للشراء
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-products">
                        <i class="fas fa-box-open"></i>
                        <h4>لا توجد منتجات</h4>
                        <p>لم يتم العثور على أي منتجات</p>
                    </div>
                </div>
            @endforelse
        </div>
        {{-- تحميل المزيد --}}
        <div class="text-center mt-4">
            <button class="btn btn-load-more">تحميل المزيد <i class="fas fa-spinner"></i></button>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- القسم 5: Banner كبير --}}
{{-- ============================================ --}}
<section class="big-banner-section">
    <div class="container">
        <div class="promo-banner-lg" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/delivery-man-8791403-7233681.png" alt="Delivery" class="banner-img">
                </div>
                <div class="col-lg-6 text-white p-5">
                    <span class="banner-badge">🚚 توصيل سريع</span>
                    <h2 class="banner-title">توصيل مجاني لجميع الطلبات</h2>
                    <p class="banner-desc">استخدم كود <strong>FREE200</strong> للحصول على توصيل مجاني للطلبات فوق 200 ر.س</p>
                    <a href="#products" class="btn btn-banner">اطلب الآن</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- القسم 6: منتجات جديدة --}}
{{-- ============================================ --}}
<section class="new-products-section">
    <div class="container">
        <div class="section-header-wrapper">
            <h3 class="section-heading">وصل حديثاً</h3>
            <a href="#" class="view-all">عرض الكل <i class="fas fa-arrow-left"></i></a>
        </div>
        <div class="swiper new-products-swiper">
            <div class="swiper-wrapper">
                @foreach($products->take(10) as $product)
                    <div class="swiper-slide">
                        <div class="mini-product-card">
                            <div class="mini-img-box">
                                <img src="{{ $product->base_image_url ?? 'https://picsum.photos/seed/'.$product->product_id.'/200/200' }}" alt="{{ $product->name }}">
                                <span class="mini-badge">جديد</span>
                            </div>
                            <h6>{{ Str::limit($product->name, 15) }}</h6>
                            <span class="mini-price">{{ number_format($product->variants->min('price'), 2) }} ر.س</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- القسم 7: الماركات --}}
{{-- ============================================ --}}
<section class="brands-section">
    <div class="container">
        <div class="section-header-wrapper">
            <h3 class="section-heading">تسوق حسب الماركة</h3>
            <a href="#" class="view-all">عرض الكل <i class="fas fa-arrow-left"></i></a>
        </div>
        <div class="swiper brands-swiper">
            <div class="swiper-wrapper">
                @foreach($brands as $brand)
                    <div class="swiper-slide">
                        <a href="{{ route('shop.index', ['brand' => $brand->brand_id]) }}" class="brand-card">
                            <div class="brand-logo-box">
                                <span class="brand-letter">{{ Str::substr($brand->name, 0, 1) }}</span>
                            </div>
                            <span class="brand-name">{{ $brand->name }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- القسم 8: مميزات المتجر --}}
{{-- ============================================ --}}
<section class="why-us-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="why-card">
                    <div class="why-icon-box" style="background: #e8f5e9;">
                        <i class="fas fa-truck" style="color: #4caf50;"></i>
                    </div>
                    <h5>توصيل سريع</h5>
                    <p>خلال 24 ساعة</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="why-card">
                    <div class="why-icon-box" style="background: #e3f2fd;">
                        <i class="fas fa-undo" style="color: #2196f3;"></i>
                    </div>
                    <h5>إرجاع مجاني</h5>
                    <p>خلال 14 يوم</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="why-card">
                    <div class="why-icon-box" style="background: #fff3e0;">
                        <i class="fas fa-shield-alt" style="color: #ff9800;"></i>
                    </div>
                    <h5>دفع آمن</h5>
                    <p>حماية 100%</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="why-card">
                    <div class="why-icon-box" style="background: #fce4ec;">
                        <i class="fas fa-headset" style="color: #e91e63;"></i>
                    </div>
                    <h5>دعم 24/7</h5>
                    <p>خدمة العملاء</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    /* ========== Hero Slider ========== */
    .hero-slider-section { padding: 30px 0; }
    .hero-swiper { border-radius: 20px; overflow: hidden; }
    .hero-slide { border-radius: 20px; height: 350px; color: white; }
    .slide-badge { background: rgba(255,255,255,0.25); padding: 6px 18px; border-radius: 25px; font-size: 0.85rem; font-weight: 600; }
    .slide-title { font-size: 2.2rem; font-weight: 900; margin: 15px 0; }
    .slide-desc { font-size: 1.1rem; opacity: 0.9; margin-bottom: 25px; }
    .btn-slide { background: white; color: #333; padding: 12px 30px; border-radius: 50px; font-weight: 700; transition: all 0.3s; }
    .btn-slide:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
    .slide-img { max-height: 280px; }
    .side-banners { display: flex; flex-direction: column; gap: 12px; height: 100%; }
    .side-banner { border-radius: 15px; cursor: pointer; transition: all 0.3s; height: calc(33.33% - 8px); display: flex; align-items: center; }
    .side-banner:hover { transform: translateX(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }

    /* ========== Categories ========== */
    .categories-section { padding: 20px 0 40px; }
    .category-card { text-align: center; text-decoration: none; display: block; transition: all 0.3s; }
    .category-card:hover { transform: translateY(-8px); }
    .category-icon-box { width: 80px; height: 80px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 2rem; transition: all 0.3s; }
    .category-card:hover .category-icon-box { border-radius: 50%; }
    .category-name { font-weight: 600; color: #333; font-size: 0.9rem; }

    /* ========== Flash Deals ========== */
    .flash-deals-section { background: #fff5f5; padding: 50px 0; border-radius: 30px; margin: 20px 0; }
    .flash-deals-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px; }
    .flash-icon { font-size: 3rem; animation: flashPulse 1s infinite; }
    @keyframes flashPulse { 0%,100%{opacity:1} 50%{opacity:0.3} }
    .flash-title { font-weight: 900; margin: 0; font-size: 1.8rem; }
    .flash-subtitle { color: #e74c3c; margin: 0; font-weight: 600; }
    .countdown-digits { display: flex; align-items: center; gap: 5px; }
    .digit-box { background: #1a1a2e; color: white; padding: 10px 8px; border-radius: 10px; text-align: center; min-width: 60px; }
    .digit-box span { display: block; font-size: 1.5rem; font-weight: 900; }
    .digit-box small { font-size: 0.65rem; opacity: 0.7; }
    .digit-sep { font-size: 1.5rem; font-weight: 900; color: #e74c3c; }
    .deal-card { background: white; border-radius: 15px; padding: 10px; position: relative; transition: all 0.3s; }
    .deal-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.1); transform: translateY(-5px); }
    .deal-badge { position: absolute; top: 10px; right: 10px; background: #e74c3c; color: white; padding: 3px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: 700; z-index: 2; }
    .deal-img { width: 100%; border-radius: 10px; height: 150px; object-fit: cover; }
    .deal-info { padding: 10px 5px 5px; }
    .deal-name { font-size: 0.85rem; font-weight: 600; margin: 5px 0; }
    .deal-price { display: flex; align-items: center; gap: 8px; margin: 5px 0; }
    .new-price { font-weight: 900; color: #e74c3c; font-size: 1rem; }
    .old-price { font-size: 0.75rem; color: #999; text-decoration: line-through; }
    .deal-progress { height: 5px; background: #eee; border-radius: 5px; margin: 8px 0; overflow: hidden; }
    .deal-progress .progress-bar { height: 100%; background: linear-gradient(90deg, #e74c3c, #f39c12); border-radius: 5px; }
    .sold-text { font-size: 0.7rem; color: #999; }

    /* ========== Featured Products ========== */
    .featured-products-section { padding: 50px 0; }
    .section-header-wrapper { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; }
    .section-heading { font-weight: 900; font-size: 1.8rem; position: relative; padding-right: 20px; }
    .section-heading::before { content: ''; position: absolute; right: 0; top: 5px; bottom: 5px; width: 5px; background: var(--primary, #00b894); border-radius: 5px; }
    .section-tabs { display: flex; gap: 5px; background: #f0f0f0; padding: 5px; border-radius: 10px; }
    .tab-btn { border: none; background: transparent; padding: 8px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 0.85rem; }
    .tab-btn.active { background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1); color: var(--primary, #00b894); }
    .view-all { color: var(--primary, #00b894); text-decoration: none; font-weight: 600; font-size: 0.9rem; }
    .product-card-final { background: white; border-radius: 15px; overflow: hidden; transition: all 0.3s; border: 1px solid #f0f0f0; height: 100%; }
    .product-card-final:hover { box-shadow: 0 10px 40px rgba(0,0,0,0.1); transform: translateY(-5px); }
    .product-img-container { position: relative; overflow: hidden; height: 220px; background: #fafafa; }
    .product-img-main { width: 100%; height: 100%; object-fit: contain; padding: 15px; transition: all 0.5s; }
    .product-card-final:hover .product-img-main { transform: scale(1.08); }
    .product-quick-actions { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); display: flex; flex-direction: column; gap: 8px; opacity: 0; transition: all 0.3s; }
    .product-card-final:hover .product-quick-actions { opacity: 1; }
    .quick-btn { width: 35px; height: 35px; border-radius: 50%; border: none; background: white; cursor: pointer; transition: all 0.3s; font-size: 0.9rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; }
    .quick-btn:hover { transform: scale(1.1); }
    .quick-cart:hover { background: #00b894; color: white; }
    .quick-heart:hover { background: #e74c3c; color: white; }
    .quick-view:hover { background: #3498db; color: white; }
    .product-tag { position: absolute; top: 10px; right: 10px; padding: 4px 12px; border-radius: 15px; font-size: 0.7rem; font-weight: 700; }
    .tag-sale { background: #e74c3c; color: white; }
    .product-details { padding: 15px; }
    .product-brand-name { font-size: 0.75rem; color: #999; text-transform: uppercase; }
    .product-title { font-size: 0.95rem; font-weight: 600; margin: 5px 0; }
    .product-title a { color: #333; text-decoration: none; }
    .product-stars { font-size: 0.75rem; margin: 5px 0; }
    .product-stars .fa-star { color: #ddd; }
    .product-stars .fa-star.active { color: #f39c12; }
    .product-stars span { color: #999; margin-right: 5px; }
    .product-price-final { display: flex; align-items: center; gap: 8px; margin: 8px 0; }
    .price-now { font-weight: 900; color: #00b894; font-size: 1.1rem; }
    .price-before { font-size: 0.8rem; color: #999; text-decoration: line-through; }
    .btn-add-cart-full { display: block; width: 100%; padding: 10px; border-radius: 25px; border: 2px solid #00b894; background: transparent; color: #00b894; font-weight: 700; text-align: center; text-decoration: none; transition: all 0.3s; cursor: pointer; font-size: 0.85rem; margin-top: 10px; }
    .btn-add-cart-full:hover { background: #00b894; color: white; }
    .btn-load-more { background: #f0f0f0; border: none; padding: 12px 40px; border-radius: 25px; font-weight: 700; transition: all 0.3s; }
    .btn-load-more:hover { background: #00b894; color: white; }

    /* ========== Big Banner ========== */
    .big-banner-section { padding: 40px 0; }
    .promo-banner-lg { border-radius: 25px; overflow: hidden; }
    .banner-img { max-width: 100%; }
    .banner-badge { background: rgba(255,255,255,0.15); padding: 6px 18px; border-radius: 25px; font-size: 0.85rem; }
    .banner-title { font-size: 2rem; font-weight: 900; margin: 15px 0; }
    .banner-desc { opacity: 0.8; margin-bottom: 25px; }
    .btn-banner { background: #00b894; color: white; padding: 12px 35px; border-radius: 50px; font-weight: 700; border: none; transition: all 0.3s; }
    .btn-banner:hover { background: #00a381; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,184,148,0.4); color: white; }

    /* ========== Mini Product Cards ========== */
    .mini-product-card { text-align: center; background: white; border-radius: 15px; padding: 10px; transition: all 0.3s; }
    .mini-product-card:hover { box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
    .mini-img-box { position: relative; width: 120px; height: 120px; margin: 0 auto 10px; }
    .mini-img-box img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .mini-badge { position: absolute; top: 0; right: 0; background: #00b894; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.65rem; font-weight: 700; }
    .mini-price { font-weight: 700; color: #00b894; }

    /* ========== Brands ========== */
    .brands-section { padding: 40px 0; background: #fafafa; border-radius: 30px; margin: 20px 0; }
    .brand-card { text-align: center; text-decoration: none; display: block; transition: all 0.3s; }
    .brand-logo-box { width: 80px; height: 80px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .brand-letter { font-size: 1.8rem; font-weight: 900; color: #00b894; }

    /* ========== Why Us ========== */
    .why-us-section { padding: 60px 0; }
    .why-card { text-align: center; padding: 30px 15px; border-radius: 20px; transition: all 0.3s; }
    .why-card:hover { background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .why-icon-box { width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 1.5rem; }
    .why-card h5 { font-weight: 700; margin-bottom: 5px; }

    /* ========== Responsive ========== */
    @media (max-width: 768px) {
        .hero-slide { height: 250px; }
        .slide-title { font-size: 1.5rem; }
        .slide-img { max-height: 150px; }
        .section-heading { font-size: 1.4rem; }
        .section-tabs { overflow-x: auto; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Hero Swiper
    new Swiper('.hero-swiper', {
        loop: true,
        autoplay: { delay: 4000 },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    });

    // Categories Swiper
    new Swiper('.categories-swiper', {
        slidesPerView: 7, spaceBetween: 15,
        breakpoints: { 320: { slidesPerView: 3 }, 768: { slidesPerView: 5 }, 1024: { slidesPerView: 7 } }
    });

    // New Products Swiper
    new Swiper('.new-products-swiper', {
        slidesPerView: 5, spaceBetween: 15,
        breakpoints: { 320: { slidesPerView: 2 }, 768: { slidesPerView: 3 }, 1024: { slidesPerView: 5 } }
    });

    // Brands Swiper
    new Swiper('.brands-swiper', {
        slidesPerView: 6, spaceBetween: 15,
        breakpoints: { 320: { slidesPerView: 3 }, 768: { slidesPerView: 4 }, 1024: { slidesPerView: 6 } }
    });

    // Countdown Timer
    let totalSeconds = 12 * 3600 + 30 * 60;
    setInterval(() => {
        if (totalSeconds <= 0) return;
        totalSeconds--;
        const h = Math.floor(totalSeconds / 3600);
        const m = Math.floor((totalSeconds % 3600) / 60);
        const s = totalSeconds % 60;
        document.getElementById('hours').textContent = String(h).padStart(2, '0');
        document.getElementById('minutes').textContent = String(m).padStart(2, '0');
        document.getElementById('seconds').textContent = String(s).padStart(2, '0');
    }, 1000);

    // Tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
@endpush