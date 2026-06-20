{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="متجر الأناقة - وجهتك الأولى للتسوق العصري">
  <meta name="theme-color" content="#0a0a0a">
  <title>@yield('title', 'المتجر العصري')</title>
  @vite('resources/css/customer/customer.css');
</head>
<body>

  {{-- ═══════════════ HEADER ═══════════════ --}}
  <header class="main-header" id="mainHeader">
    <div class="container header-inner">
      <a href="{{ route('shop.index') }}" class="logo">أناق<span>ة</span></a>

      <nav class="nav-links">
        <a href="{{ route('shop.index') }}" class="active">الرئيسية</a>
        <a href="{{ route('shop.index') }}">المنتجات</a>
        <a href="#categories">التصنيفات</a>
        <a href="#offers">العروض</a>
        <a href="#testimonials">آراء العملاء</a>
      </nav>

      <div class="header-actions">
        <button class="icon-btn" aria-label="البحث">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" /></svg>
        </button>
        <a href="{{ route('cart.index') }}" class="icon-btn" aria-label="السلة">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" /><path d="M3 6h18" /><path d="M16 10a4 4 0 0 1-8 0" /></svg>
          @php
          $currentSessionId = request()->session()->getId();
          $cart = \App\Models\Cart::where('user_id', auth()->id())
          ->orWhere('session_id', $currentSessionId)
          ->first();
          @endphp
          <span class="cart-badge">
            <span id="cart-count" class="cart-badge">
              {{ $cart ? $cart->items()->sum('quantity') : 0 }}
            </span>
          </span>
        </a>
        <button class="icon-btn mobile-menu-btn" id="mobileMenuBtn" aria-label="القائمة">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6" /><line x1="3" y1="12" x2="21" y2="12" /><line x1="3" y1="18" x2="21" y2="18" /></svg>
        </button>
      </div>
    </div>
  </header>

  {{-- ═══════════════ MOBILE MENU ═══════════════ --}}
  <div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-panel">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <span class="logo">أناق<span>ة</span></span>
        <button id="closeMobileMenu" style="font-size:1.5rem;color:var(--color-text-secondary);">&times;</button>
      </div>
      <a href="{{ route('product.index') }}">الرئيسية</a>
      <a href="{{ route('product.index') }}">المنتجات</a>
      <a href="#categories">التصنيفات</a>
      <a href="#offers">العروض</a>
      <a href="#testimonials">آراء العملاء</a>
    </div>
  </div>

  {{-- ═══════════════ MAIN CONTENT ═══════════════ --}}
  <main>
    @yield('content')
  </main>

  {{-- ═══════════════ FOOTER ═══════════════ --}}
  <footer class="main-footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="{{ route('product.index') }}" class="logo" style="color:#fff;">أناق<span>ة</span></a>
          <p>
            وجهتك الأولى للتسوق العصري. نقدم لك أفضل المنتجات العالمية بتجربة فريدة وخدمة استثنائية.
          </p>
        </div>
        <div class="footer-col">
          <h4>روابط سريعة</h4>
          <ul>
            <li><a href="{{ route('shop.index') }}">الرئيسية</a></li>
            <li><a href="{{ route('product.index') }}">المنتجات</a></li>
            <li><a href="#">من نحن</a></li>
            <li><a href="#">اتصل بنا</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>خدمة العملاء</h4>
          <ul>
            <li><a href="#">الشحن والتوصيل</a></li>
            <li><a href="#">الإرجاع والاستبدال</a></li>
            <li><a href="#">الأسئلة الشائعة</a></li>
            <li><a href="#">سياسة الخصوصية</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>تواصل معنا</h4>
          <ul>
            <li><a href="#">info@anaqa.com</a></li>
            <li><a href="#">+966 50 000 0000</a></li>
            <li><a href="#">الرياض، المملكة العربية السعودية</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <span>© {{date('y')}} متجر الأناقة. جميع الحقوق محفوظة.</span>
        <span>صنع بكل ❤️ في المملكة العربية السعودية</span>
      </div>
    </div>
  </footer>

  {{-- ═══════════════ SCRIPTS ═══════════════ --}}
  @vite('resources/js/customer.js');

  @stack('scripts')
</body>
</html>