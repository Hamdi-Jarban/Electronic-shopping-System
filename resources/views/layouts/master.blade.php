<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Responsive Bootstrap4 Shop Template, Created by Imran Hossain from https://imransdesign.com/">

  <!-- title -->
  <title>@yield('title','المجتمع التجاري')</title>

  <!-- favicon -->
  <link rel="shortcut icon" type="image/png" href="{{asset('assets/img/favicon.png')}}">
  <!-- google font -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
  <!-- fontawesome -->
  <link rel="stylesheet" href="{{ asset('assets/css/all.min.css')}}">
  <!-- bootstrap -->
  <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
  <!-- owl carousel -->
  <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.css')}}">
  <!-- magnific popup -->
  <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css')}}">
  <!-- animate css -->
  <link rel="stylesheet" href="{{ asset('assets/css/animate.css')}}">
  <!-- mean menu css -->
  <link rel="stylesheet" href="{{ asset('assets/css/meanmenu.min.css')}}">
  <!-- main style -->
  <link rel="stylesheet" href="{{ asset('assets/css/main.css')}}">
  <!-- responsive -->
  <link rel="stylesheet" href="{{ asset('assets/css/responsive.css')}}">
</head>
<body>

  <!--PreLoader-->
  <div class="loader">
    <div class="loader-inner">
      <div class="circle"></div>
    </div>
  </div>
  <!--PreLoader Ends-->

  <!-- header -->
  <div class="top-header-area" id="sticker">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-sm-12 text-center">
          <div class="main-menu-wrap">
            <!-- logo -->
            <div class="site-logo">
              <a href="index.html">
                <img src="assets/img/logo.png" alt="">
              </a>
            </div>
            <!-- logo -->

            <!-- menu start -->
            <nav class="main-menu">
              <ul>
                <li class="current-list-item"><a href="#">الرئيسية</a>
                  <ul class="sub-menu">
                    <li><a href="index.html">  متحركه</a></li>
                    <li><a href="index_2.html">ثابتة</a></li>
                  </ul>
                </li>
                <li><a href="about.html">حول</a></li>
                <li><a href="#">الصفحات</a>
                  <ul class="sub-menu">
                    <li><a href="404.html">صفحه الخطأ  </a></li>
                    <li><a href="about.html">حولنا</a></li>
                    <li><a href="cart.html">السله</a></li>
                    <li><a href="checkout.html">Check Out</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="news.html">News</a></li>
                    <li><a href="shop.html">تسوق</a></li>
                  </ul>
                </li>
                <li><a href="news.html"><New></New>s</a>
                  <ul class="sub-menu">
                    <li><a href="news.html">News</a></li>
                    <li><a href="single-news.html">Single News</a></li>
                  </ul>
                </li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="shop.html">تسوق</a>
                  <ul class="sub-menu">
                    <li><a href="shop.html">Shop</a></li>
                    <li><a href="checkout.html">Check Out</a></li>
                    <li><a href="single-product.html">Single Product</a></li>
                    <li><a href="cart.html">Cart</a></li>
                  </ul>
                </li>
                <li>
                  <div class="header-icons">
                    <a class="shopping-cart" href="cart.html"><i class="fas fa-shopping-cart"></i></a>
                    <a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>
                  </div>
                </li>
              </ul>
            </nav>
            <a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
            <div class="mobile-menu"></div>
            <!-- menu end -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end header -->

  <!-- search area -->
  <div class="search-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <span class="close-btn"><i class="fas fa-window-close"></i></span>
          <div class="search-bar">
            <div class="search-bar-tablecell">
              <h3>Search For:</h3>
              <input type="text" placeholder="Keywords">
              <button type="submit">بحث <i class="fas fa-search"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end search area -->

  @yield('content')

  <!-- footer -->
  <div class="footer-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="footer-box about-widget">
            <h2 class="widget-title">من نحن</h2>
            <p>
              نحن متجر متخصص في توفير كافة المستلزمات بأقل التكاليف وباقل وقت
              ممكن
            </p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="footer-box get-in-touch">
            <h2 class="widget-title">تواصل معنا</h2>
            <ul>
              <li>اليمن -إب -شارع العدين.</li>
              <li>hmdyjrban129@gmail.com</li>
              <li>+96778461645</li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="footer-box pages">
            <h2 class="widget-title">الصفحات</h2>
            <ul>
              <li><a href="index.html">الرئيسية</a></li>
              <li><a href="about.html">من نحن</a></li>
              <li><a href="services.html">المتجر</a></li>
              <li><a href="news.html">الأخبار</a></li>
              <li><a href="contact.html">اتصل بنا</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="footer-box subscribe">
            <h2 class="widget-title">اشترك معنا</h2>
            <p>
              اشترك في قائمتنا البريدية ليصلك آخر التحديثات والأخبار.
            </p>
            <form action="index.html">
              <input type="email" placeholder="البريد الإلكتروني">
              <button type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- end footer -->

  <!-- copyright -->
  <div class="copyright">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-12">
          <p>
            Copyrights &copy; 2019 - <a href="https://imransdesign.com/">Imran Hossain</a>,  All Rights Reserved.<br>
            Distributed By - <a href="https://themewagon.com/">Themewagon</a>
          </p>
        </div>
        <div class="col-lg-6 text-right col-md-12">
          <div class="social-icons">
            <ul>
              <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
              <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
              <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
              <li><a href="#" target="_blank"><i class="fab fa-linkedin"></i></a></li>
              <li><a href="#" target="_blank"><i class="fab fa-dribbble"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end copyright -->

  <!-- jquery -->
  <script src="{{ asset('assets/js/jquery-1.11.3.min.js')}}"></script>
  <!-- bootstrap -->
  <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
  <!-- count down -->
  <script src="{{ asset('assets/js/jquery.countdown.js')}}"></script>
  <!-- isotope -->
  <script src="{{ asset('assets/js/jquery.isotope-3.0.6.min.js')}}"></script>
  <!-- waypoints -->
  <script src="{{ asset('assets/js/waypoints.js')}}"></script>
  <!-- owl carousel -->
  <script src="{{ asset('assets/js/owl.carousel.min.js')}}"></script>
  <!-- magnific popup -->
  <script src="{{ asset('assets/js/jquery.magnific-popup.min.js')}}"></script>
  <!-- mean menu -->
  <script src="{{ asset('assets/js/jquery.meanmenu.min.js')}}"></script>
  <!-- sticker js -->
  <script src="{{ asset('assets/js/sticker.js')}}"></script>
  <!-- main js -->
  <script src="{{ asset('assets/js/main.js')}}"></script>

</body>
</html>