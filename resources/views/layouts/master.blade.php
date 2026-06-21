<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title','الصفحةالرئيسية')</title>

  <!-- الخط العربي Tajawal -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
  @vite('resources/css/checkout/master.css')
  @vite('resources/js/lucide.min.js')
  <!-- مكتبة الأيقونات Lucide (خفيفة جداً) -->


</head>
<body>

  <!-- ==================== رأس الموقع ==================== -->
  <header class="site-header" id="siteHeader">

    <!-- شريط الإعلانات الدوّار -->
    <div class="announcement-bar" id="announcementBar">
      <div class="announcement-inner">
        <div class="announcement-slides" id="announcementSlides">
          <div class="announcement-slide active">
            <span class="announcement-icon">🔥</span>
            <span class="announcement-text">خصم <strong>30%</strong> على جميع الإلكترونيات - لفترة محدودة</span>
          </div>
          <div class="announcement-slide">
            <span class="announcement-icon">🚚</span>
            <span class="announcement-text">شحن مجاني للطلبات فوق <strong>200 ريال</strong></span>
          </div>
          <div class="announcement-slide">
            <span class="announcement-icon">🎁</span>
            <span class="announcement-text">عروض حصرية للأعضاء الجدد - <strong>سجل الآن</strong></span>
          </div>
        </div>
        <!-- أزرار التنقل -->
        <div class="announcement-arrows">
          <button class="announcement-arrow" id="annPrev"><i data-lucide="chevron-right"></i></button>
          <button class="announcement-arrow" id="annNext"><i data-lucide="chevron-left"></i></button>
        </div>
        <!-- زر الإغلاق -->
        <button class="announcement-close" id="annClose"><i data-lucide="x"></i></button>
      </div>
    </div>

    <!-- الرأس الرئيسي -->
    <div class="main-header">
      <div class="container">
        <!-- زر قائمة الجوال -->
        <button class="mobile-toggle action-btn" id="mobileMenuToggle">
          <i data-lucide="menu"></i>
        </button>

        <!-- الشعار -->
        <a href="#" class="logo">
          <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 180 50'%3E%3Ctext x='10' y='35' font-family='Tajawal,sans-serif' font-size='28' font-weight='800' fill='%230F1A2E'%3Eسوق%3C/text%3E%3Ctext x='75' y='35' font-family='Tajawal,sans-serif' font-size='28' font-weight='800' fill='%23D4AF37'%3E.كوم%3C/text%3E%3C/svg%3E" alt="منصة سوق">
        </a>

        <!-- شريط البحث -->
        <div class="search-wrapper" id="searchWrapper">
          <div class="search-bar">
            <input type="search" class="search-input" placeholder="ابحث عن المنتجات، الماركات..." id="searchInput" autocomplete="off">
            <button class="search-voice-btn" title="بحث صوتي"><i data-lucide="mic"></i></button>
            <button class="search-submit-btn"><i data-lucide="search"></i></button>
          </div>
          <!-- اقتراحات البحث -->
          <div class="search-suggestions" id="searchSuggestions">
            <div class="suggestion-group-title">
              اقتراحات البحث
            </div>
            <div class="suggestion-item">
              <i data-lucide="search" class="suggestion-icon"></i> <span>هاتف <span class="suggestion-highlight">آيفون 15</span> برو</span>
            </div>
            <div class="suggestion-item">
              <i data-lucide="search" class="suggestion-icon"></i> <span>ساعة <span class="suggestion-highlight">آبل</span> الترا</span>
            </div>
            <div class="suggestion-item">
              <i data-lucide="search" class="suggestion-icon"></i> <span>سماعات <span class="suggestion-highlight">لاسلكية</span> مع عزل ضوضاء</span>
            </div>
          </div>
        </div>

        <!-- أيقونات المستخدم -->
        <div class="user-actions">
          <!-- زر البحث للجوال -->
          <button class="mobile-search-toggle action-btn" id="mobileSearchToggle"><i data-lucide="search"></i></button>

          <!-- المفضلة -->
          <button class="action-btn" title="المفضلة">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
  <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
</svg>
            <span class="badge wishlist-badge">3</span>
          </button>

          <!-- السلة مع السعر -->
          <button class="cart-btn action-btn" id="cartToggle">
            <i data-lucide="shopping-cart"></i>
            <span class="badge">2</span>
            <span class="cart-total">1,250 ر.س</span>
          </button>

          <!-- حساب المستخدم مع قائمة منسدلة -->
          <div class="account-dropdown desktop-only" id="accountDropdown">
            <button class="action-btn" id="accountBtn"><i data-lucide="user"></i></button>
            <div class="account-menu">
              <a href="#"><i data-lucide="log-in"></i> تسجيل الدخول</a>
              <a href="#"><i data-lucide="user-plus"></i> حساب جديد</a>
              <div class="account-divider"></div>
              <a href="#"><i data-lucide="package"></i> طلباتي</a>
              <a href="#"><i data-lucide="settings"></i> الإعدادات</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- القائمة الضخمة (سطح المكتب) -->
    <div class="nav-container" id="navContainer">
      <div class="container">
        <ul class="nav-list">
          <li class="nav-item" id="megaMenuItem">
            <span class="nav-link">فئات المنتجات <i data-lucide="chevron-down"></i></span>
            <div class="mega-menu">
              <div class="mega-col">
                <div class="mega-col-title">
                  <i data-lucide="smartphone"></i> إلكترونيات
                </div>
                <a href="#" class="mega-sub-link">هواتف ذكية</a>
                <a href="#" class="mega-sub-link">أجهزة لوحية</a>
                <a href="#" class="mega-sub-link">لابتوبات</a>
                <a href="#" class="mega-sub-link">سماعات</a>
                <a href="#" class="mega-sub-link">كاميرات</a>
              </div>
              <div class="mega-col">
                <div class="mega-col-title">
                  <i data-lucide="shirt"></i> أزياء
                </div>
                <a href="#" class="mega-sub-link">ملابس رجالية</a>
                <a href="#" class="mega-sub-link">ملابس نسائية</a>
                <a href="#" class="mega-sub-link">أحذية</a>
                <a href="#" class="mega-sub-link">إكسسوارات</a>
                <a href="#" class="mega-sub-link">ساعات</a>
              </div>
              <div class="mega-col">
                <div class="mega-col-title">
                  <i data-lucide="home"></i> المنزل
                </div>
                <a href="#" class="mega-sub-link">أثاث</a>
                <a href="#" class="mega-sub-link">أجهزة مطبخ</a>
                <a href="#" class="mega-sub-link">ديكورات</a>
                <a href="#" class="mega-sub-link">مفروشات</a>
              </div>
              <div class="mega-featured">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Crect width='120' height='120' fill='%23F1F5F9' rx='8'/%3E%3Ctext x='60' y='60' text-anchor='middle' dominant-baseline='middle' font-size='36'%3E📱%3C/text%3E%3C/svg%3E" alt="عرض">
                <strong>أحدث الهواتف</strong>
                <p style="font-size:0.8rem; color:var(--text-light);">
                  خصم يصل إلى 30%
                </p>
                <a href="#" class="btn-accent-sm">تسوق الآن</a>
              </div>
            </div>
          </li>
          <li class="nav-item"><a href="#" class="nav-link">عروض اليوم</a></li>
          <li class="nav-item"><a href="#" class="nav-link">الأكثر مبيعاً</a></li>
          <li class="nav-item"><a href="#" class="nav-link">ماركات عالمية</a></li>
        </ul>
      </div>
    </div>
  </header>

  <!-- ==================== درج الجوال (قائمة + حساب) ==================== -->
  <div class="overlay" id="overlay"></div>
  <div class="mobile-drawer" id="mobileDrawer">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
      <h3>القائمة</h3>
      <button id="mobileDrawerClose"><i data-lucide="x"></i></button>
    </div>
    <!-- نسخة مبسطة من القائمة والبحث للجوال -->
    <div class="search-bar" style="margin-bottom:1.2rem; border:1px solid var(--border);">
      <input type="search" placeholder="ابحث..." style="flex:1; padding:0.6rem; border:none; background:transparent; outline:none;">
      <button style="padding:0.6rem; color:var(--accent);"><i data-lucide="search"></i></button>
    </div>
    <div style="display:flex; flex-direction:column; gap:0.5rem;">
      <a href="#" style="padding:0.75rem; border-bottom:1px solid var(--border); font-weight:500;">📱 إلكترونيات</a>
      <a href="#" style="padding:0.75rem; border-bottom:1px solid var(--border);">👕 أزياء</a>
      <a href="#" style="padding:0.75rem; border-bottom:1px solid var(--border);">🏠 المنزل</a>
      <a href="#" style="padding:0.75rem; border-bottom:1px solid var(--border);">🔥 عروض اليوم</a>
    </div>
    <div class="account-divider" style="margin:1.5rem 0;"></div>
    <a href="#" style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem;"><i data-lucide="user"></i> حسابي</a>
    <a href="#" style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem;"><i data-lucide="heart"></i> المفضلة (3)</a>
    <a href="#" style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem;"><i data-lucide="shopping-cart"></i> السلة (2) - 1,250 ر.س</a>
  </div>
  @vite('resources/js/master.js')

</body>
</html>