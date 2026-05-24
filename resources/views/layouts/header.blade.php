<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title',"لوحة التحكم")</title>
            @vite('resources/css/main.css');

</head>
<body>

<!-- زر القائمة للجوال (للوصول: aria-label و aria-expanded) -->
<button class="sidebar-toggle" id="sidebarToggle" 
        aria-label="فتح القائمة الجانبية" aria-expanded="false" 
        aria-controls="mainSidebar">
    ☰
</button>

<!-- Header ثابت -->
<header class="main-header" role="banner">
    <div class="header-logo">
        <img src="images/settings/slogan.png" alt="شعار الموقع">
        <span class="site-name">التسوق الإلكتروني</span>
    </div>

    <nav class="header-nav" aria-label="التنقل العلوي">
        <a href="#" class="btn btn-header">
            الطلبات <span class="badge"></span>
        </a>
        <a href="#" class="btn btn-header">
            الإشعارات <span class="badge">5</span>
        </a>
        <a href="#" class="btn btn-header">
            العملاء <span class="badge">5</span>
        </a>
        <a href="#" class="btn btn-header">
            الكميات <span class="badge">5</span>
        </a>

        <div class="user-info">
            <img src="  images/settings/slogan.png" alt="صورة المستخدم">
            <span></span>
        </div>
    </nav>
</header>

<!-- الحاوية الرئيسية: تبدأ بعد الهيدر مباشرة -->
<div class="app-container">
<!-- الشريط الجانبي (يحتوي على role و aria-label) -->
<aside class="main-sidebar" id="mainSidebar" role="navigation" aria-label="القائمة الرئيسية">
    <ul class="sidebar-nav">
        <li><a href="#" class="btn btn-sidebar active">الرئيسية</a></li>
        <li><a href="home/about" class="btn btn-sidebar">الطلبات</a></li>
        <li><a href="product/index" class="btn btn-sidebar">المنتجات</a></li>
        <li><a href="#" class="btn btn-sidebar">التقارير</a></li>
        <li><a href="#" class="btn btn-sidebar">الموارد البشرية</a></li>
        <li><a href="user" class="btn btn-sidebar">المستخدمين</a></li>
        <li><a href="#" class="btn btn-sidebar">العملاء</a></li>
        <li><a href="#" class="btn btn-sidebar">الفواتير</a></li>
        <li><a href="#" class="btn btn-sidebar">الإعدادات</a></li>
    </ul>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<main class="main-content" id="mainContent" role="main">
    <!-- هنا سيتم وضع المحتوى الخاص بكل صفحة -->


<div class="main">
   @yield('content')

</div>
</main><!-- نهاية main-content -->
</div><!-- نهاية app-container -->

<!-- سكريبت بسيط للتحكم بالسايدبار على الجوال مع مراعاة الوصول -->
@push('scripts')

<script>
(function() {
    const sidebar = document.getElementById('mainSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        toggleBtn.setAttribute('aria-expanded', 'true');
        toggleBtn.setAttribute('aria-label', 'إغلاق القائمة الجانبية');
        // حفظ التركيز على أول عنصر قائمة للوصول
        const firstLink = sidebar.querySelector('a');
        if(firstLink) firstLink.focus();
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        toggleBtn.setAttribute('aria-expanded', 'false');
        toggleBtn.setAttribute('aria-label', 'فتح القائمة الجانبية');
        toggleBtn.focus(); // إعادة التركيز للزر
    }

    toggleBtn.addEventListener('click', function() {
        if (sidebar.classList.contains('open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    overlay.addEventListener('click', closeSidebar);

    // إغلاق بالسكيب من لوحة المفاتيح (Escape)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
            closeSidebar();
        }
    });
})();
</script>

</body>
</html>