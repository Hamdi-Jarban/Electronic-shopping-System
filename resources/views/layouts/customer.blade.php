<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'متجر السوبر ماركت - تسوق أونلاين')</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🛒</text></svg>">
    
    {{-- Bootstrap 5 RTL --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- Google Fonts - خط عربي احترافي --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    {{-- Swiper.js للسلايدرات --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    
    {{-- AOS Animation --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    
    {{-- ملفاتنا المخصصة --}}
    @vite('resources/css/customer.css')
    @stack('styles')
</head>
<body>

    {{-- ============================================ --}}
    {{-- شريط علوي (Top Bar) - معلومات سريعة --}}
    {{-- ============================================ --}}
    <div class="top-bar d-none d-md-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="top-bar-left">
                        <span><i class="fas fa-phone-alt"></i> 9200 123 456</span>
                        <span class="mx-3"><i class="fas fa-envelope"></i> info@supermarket.com</span>
                    </div>
                </div>
                <div class="col-md-6 text-start">
                    <div class="top-bar-right">
                        <span><i class="fas fa-truck"></i> توصيل مجاني للطلبات فوق 200 ر.س</span>
                        <span class="mx-3"><i class="fas fa-clock"></i> توصيل خلال 24 ساعة</span>
                        <a href="#" class="language-switch">English</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- شريط التنقل الرئيسي (Navbar) --}}
    {{-- ============================================ --}}
    <nav class="navbar navbar-expand-lg main-navbar sticky-top">
        <div class="container">
            {{-- الشعار --}}
            <a class="navbar-brand" href="{{ route('shop.index') }}">
                <div class="brand-icon">
                    <i class="fas fa-shopping-basket"></i>
                </div>
                <div class="brand-text">
                    <span class="brand-name">سوبر ماركت</span>
                    <span class="brand-slogan">تسوق بذكاء</span>
                </div>
            </a>

            {{-- زر القائمة للجوال --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- محتوى القائمة --}}
            <div class="collapse navbar-collapse" id="mainNavbar">
                {{-- القائمة الرئيسية --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shop.index') ? 'active' : '' }}" href="{{ route('shop.index') }}">
                            <i class="fas fa-home"></i> الرئيسية
                        </a>
                    </li>
                    <li class="nav-item dropdown mega-dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-th-large"></i> الأقسام
                        </a>
                        <div class="dropdown-menu mega-menu">
                            <div class="container">
                                <div class="row">
                                    @php
                                        $menuCategories = \App\Models\Category::whereNull('parent_category_id')->with('children')->limit(6)->get();
                                    @endphp
                                    @foreach($menuCategories as $cat)
                                        <div class="col-lg-2 col-md-4">
                                            <h6 class="mega-title">{{ $cat->name }}</h6>
                                            @if($cat->children->isNotEmpty())
                                                <ul class="mega-list">
                                                    @foreach($cat->children->take(5) as $child)
                                                        <li><a href="{{ route('shop.index', ['category' => $child->category_id]) }}">{{ $child->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="col-lg-2 col-md-4">
                                        <div class="mega-banner">
                                            <img src="https://via.placeholder.com/200x200/00b894/ffffff?text=خصم+30%25" alt="عرض">
                                            <p>خصم 30% على جميع المنتجات</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#offers-section">
                            <i class="fas fa-fire"></i> العروض
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-tags"></i> أفضل المبيعات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-newspaper"></i> جديدنا
                        </a>
                    </li>
                </ul>

                {{-- شريط البحث --}}
                <form action="{{ route('shop.index') }}" method="GET" class="search-form me-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث عن منتج..." value="{{ request('search') }}">
                        <button class="btn btn-search" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                {{-- أيقونات المستخدم --}}
                <ul class="navbar-nav user-actions">
                    {{-- المفضلة --}}
                    <li class="nav-item">
                        <a class="nav-link icon-link" href="#" data-bs-toggle="tooltip" title="المفضلة">
                            <i class="far fa-heart"></i>
                            <span class="icon-badge wishlist-count">0</span>
                        </a>
                    </li>

                    {{-- المقارنة --}}
                    <li class="nav-item">
                        <a class="nav-link icon-link" href="#" data-bs-toggle="tooltip" title="مقارنة المنتجات">
                            <i class="fas fa-balance-scale"></i>
                            <span class="icon-badge compare-count">0</span>
                        </a>
                    </li>

                    {{-- السلة --}}
                    <li class="nav-item dropdown cart-dropdown">
                        <a class="nav-link icon-link cart-link" href="#" data-bs-toggle="dropdown" title="سلة التسوق">
                            <i class="fas fa-shopping-cart"></i>
                        @php
    // جلب السلة الحالية سواء للمسجل أو الزائر عبر الـ session_id
    $currentCart = auth()->check() 
        ? auth()->user()->cart 
        : \App\Models\Cart::where('session_id', request()->session()->getId())->first();

    // حساب إجمالي كميات المنتجات (وليس فقط عدد الأسطر) ليعطي رقماً دقيقاً
    $cartCount = $currentCart ? $currentCart->items->sum('quantity') : 0;
            @endphp
                            <span class="icon-badge cart-count {{ $cartCount > 0 ? 'has-items' : '' }}">{{ $cartCount }}</span>
                        </a>
                        {{-- منسدلة السلة السريعة --}}
                        <div class="dropdown-menu dropdown-menu-end cart-preview">
                            <div class="cart-preview-header">
                                <h6>سلة التسوق</h6>
                                <a href="{{ route('cart.index') }}">عرض الكل</a>
                            </div>
                            <div class="cart-preview-items">
                               @if($currentCart && $currentCart->items->count() > 0)
                                        @foreach($currentCart->items->take(3) as $item)
                                        <div class="cart-preview-item">
                                            <img src="{{ $item->variant->product->base_image_url ?? 'https://via.placeholder.com/60' }}" alt="">
                                            <div class="item-info">
                                                <p class="item-name">{{ Str::limit($item->variant->product->name, 25) }}</p>
                                                <p class="item-price">{{ number_format($item->variant->price, 2) }} ر.س × {{ $item->quantity }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-cart-preview">
                                        <i class="fas fa-shopping-cart"></i>
                                        <p>السلة فارغة</p>
                                    </div>
                                @endif
                            </div>
                            <div class="cart-preview-footer">
                                <a href="{{ route('cart.index') }}" class="btn btn-primary btn-sm w-100">عرض السلة</a>
                            </div>
                        </div>
                    </li>

                    {{-- حساب المستخدم --}}
                    @auth
                        <li class="nav-item dropdown user-dropdown">
                            <a class="nav-link user-link" href="#" data-bs-toggle="dropdown">
                                <div class="user-avatar">
                                    {{ Str::substr(auth()->user()->full_name, 0, 1) }}
                                </div>
                                <span class="d-none d-lg-inline">{{ Str::limit(auth()->user()->full_name, 10) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end user-menu">
                                <li>
                                    <div class="user-menu-header">
                                        <div class="user-avatar-lg">{{ Str::substr(auth()->user()->full_name, 0, 1) }}</div>
                                        <div>
                                            <strong>{{ auth()->user()->full_name }}</strong>
                                            <small>{{ auth()->user()->email }}</small>
                                        </div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-list-check"></i> طلباتي</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-heart"></i> المفضلة</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user-edit"></i> تعديل الملف</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-map-marker-alt"></i> العناوين</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="document.getElementById('logout-form').submit()">
                                        <i class="fas fa-sign-out-alt"></i> تسجيل خروج
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-login">
                                <i class="fas fa-sign-in-alt"></i> دخول / تسجيل
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- ============================================ --}}
    {{-- أزرار عائمة جانبية --}}
    {{-- ============================================ --}}
    <div class="floating-buttons">
        {{-- دردشة مباشرة --}}
        <button class="floating-btn chat-btn" data-bs-toggle="tooltip" title="دردشة مباشرة">
            <i class="fas fa-comments"></i>
        </button>

        {{-- العودة للأعلى --}}
        <button class="floating-btn scroll-top-btn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
            <i class="fas fa-chevron-up"></i>
        </button>
    </div>

    {{-- ============================================ --}}
    {{-- المحتوى الرئيسي --}}
    {{-- ============================================ --}}
    <main>
        {{-- رسائل التنبيه --}}
        @if(session('success'))
            <div class="alert alert-success alert-floating" id="alertMessage">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-floating" id="alertMessage">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Yield المحتوى --}}
        @yield('content')
    </main>

    {{-- ============================================ --}}
    {{-- الفوتر (Footer) --}}
    {{-- ============================================ --}}
    <footer class="main-footer">
        {{-- الجزء العلوي من الفوتر --}}
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    {{-- عن المتجر --}}
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-brand">
                            <i class="fas fa-shopping-basket"></i>
                            <h4>سوبر ماركت</h4>
                        </div>
                        <p class="footer-about">متجرك الإلكتروني الموثوق لتسوق جميع احتياجاتك اليومية بأفضل الأسعار وجودة مضمونة مع توصيل سريع لجميع مدن اليمن.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-tiktok"></i></a>
                            <a href="#"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>

                    {{-- روابط سريعة --}}
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h5 class="footer-title">روابط سريعة</h5>
                        <ul class="footer-links">
                            <li><a href="#">من نحن</a></li>
                            <li><a href="#">اتصل بنا</a></li>
                            <li><a href="#">سياسة الخصوصية</a></li>
                            <li><a href="#">الشروط والأحكام</a></li>
                            <li><a href="#">الأسئلة الشائعة</a></li>
                        </ul>
                    </div>

                    {{-- خدمة العملاء --}}
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h5 class="footer-title">خدمة العملاء</h5>
                        <ul class="footer-links">
                            <li><a href="#">حسابي</a></li>
                            <li><a href="#">طلباتي</a></li>
                            <li><a href="#">الإرجاع والاستبدال</a></li>
                            <li><a href="#">سياسة الشحن</a></li>
                            <li><a href="#">تتبع الطلب</a></li>
                        </ul>
                    </div>

                    {{-- معلومات الاتصال --}}
                    <div class="col-lg-4 col-md-6 mb-4">
                        <h5 class="footer-title">تواصل معنا</h5>
                        <ul class="footer-contact">
                            <li><i class="fas fa-map-marker-alt"></i> صنعاء,   اليمن</li>
                            <li><i class="fas fa-phone-alt"></i> +96778461645</li>
                            <li><i class="fas fa-envelope"></i> jarbanhamdi@gmai.com</li>
                            <li><i class="fas fa-clock"></i> دوام العمل: 24/7</li>
                        </ul>
                        {{-- تطبيقات الجوال --}}
                        <div class="app-buttons mt-3">
                            <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" height="45"></a>
                            <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/5/5d/Available_on_the_App_Store_%28black%29.png" alt="App Store" height="45"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- الجزء السفلي من الفوتر --}}
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; {{ date('Y') }} سوبر ماركت. جميع الحقوق محفوظة.</p>
                    </div>
                    <div class="col-md-6 text-start">
                        <div class="payment-methods">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-amex"></i>
                            <span>مدى</span>
                            <span>Apple Pay</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- ============================================ --}}
    {{-- Quick View Modal --}}
    {{-- ============================================ --}}
    <div class="modal fade" id="quickViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="quickViewContent">
                    {{-- يتم تعبئته عبر AJAX --}}
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- السكربتات --}}
    {{-- ============================================ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // AOS Animation
        AOS.init({
            duration: 800,
            once: true,
        });

        // تفعيل tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // اختفاء التنبيه تلقائياً
        setTimeout(function() {
            var alert = document.getElementById('alertMessage');
            if (alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 4000);

        // زر العودة للأعلى - يظهر عند التمرير
        window.addEventListener('scroll', function() {
            var scrollBtn = document.querySelector('.scroll-top-btn');
            if (scrollBtn) {
                if (window.scrollY > 300) {
                    scrollBtn.style.opacity = '1';
                    scrollBtn.style.visibility = 'visible';
                } else {
                    scrollBtn.style.opacity = '0';
                    scrollBtn.style.visibility = 'hidden';
                }
            }
        });

        // تحديث السلة عبر AJAX
        function updateCartCount() {
            fetch('/cart/count')
                .then(res => res.json())
                .then(data => {
                    document.querySelectorAll('.cart-count').forEach(el => {
                        el.textContent = data.count;
                        if (data.count > 0) {
                            el.classList.add('has-items');
                        } else {
                            el.classList.remove('has-items');
                        }
                    });
                });
        }

        // إضافة للسلة
        function addToCart(variantId) {
            fetch(`/cart/add/${variantId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateCartCount();
                    Swal.fire({
                        icon: 'success',
                        title: 'تمت الإضافة!',
                        text: 'تم إضافة المنتج إلى سلة التسوق',
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                    });
                }
            });
        }
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>