
      // ========== تهيئة الأيقونات ==========
      lucide.createIcons(); 

      // ========== العناصر ==========
      const siteHeader = document.getElementById('siteHeader');
      const announcementBar = document.getElementById('announcementBar');
      const announcementSlides = document.getElementById('announcementSlides');
      const slides = announcementSlides.querySelectorAll('.announcement-slide');
      let currentSlide = 0;
      let slideInterval;
      const slideCount = slides.length;

      // ========== شريط الإعلانات الدوّار ==========
      function goToSlide(index) {
      slides.forEach((slide, i) => {
      if (i === currentSlide) slide.classList.add('exit');
      else slide.classList.remove('exit');
      slide.classList.remove('active');
      });
      currentSlide = (index + slideCount) % slideCount;
      slides[currentSlide].classList.add('active');
      slides[currentSlide].classList.remove('exit');
      }

      function nextSlide() { goToSlide(currentSlide + 1); }
      function prevSlide() { goToSlide(currentSlide - 1); }

      function startSlideShow() {
      stopSlideShow();
      slideInterval = setInterval(nextSlide, 4000);
      }
      function stopSlideShow() {
      clearInterval(slideInterval);
      }

      // أزرار التنقل
      document.getElementById('annPrev').addEventListener('click', () => {
      prevSlide();
      startSlideShow(); // إعادة التشغيل بعد التدخل اليدوي
      });
      document.getElementById('annNext').addEventListener('click', () => {
      nextSlide();
      startSlideShow();
      });

      // إيقاف مؤقت عند التحويم
      announcementBar.addEventListener('mouseenter', stopSlideShow);
      announcementBar.addEventListener('mouseleave', startSlideShow);

      // زر الإغلاق
      document.getElementById('annClose').addEventListener('click', () => {
      announcementBar.classList.add('closed');
      // تخزين الحالة مؤقتًا
      sessionStorage.setItem('announcementClosed', 'true');
      });

      // التحقق من الحالة عند التحميل
      if (sessionStorage.getItem('announcementClosed') === 'true') {
      announcementBar.classList.add('closed');
      }

      // بدء العرض
      startSlideShow();

      // ========== السلوك اللاصق ==========
      window.addEventListener('scroll', () => {
      if (window.scrollY > 60) {
      siteHeader.classList.add('scrolled');
      } else {
      siteHeader.classList.remove('scrolled');
      }
      }, { passive: true });

      // ========== اقتراحات البحث ==========
      const searchInput = document.getElementById('searchInput');
      const searchSuggestions = document.getElementById('searchSuggestions');

      searchInput.addEventListener('focus', () => searchSuggestions.classList.add('active'));
      searchInput.addEventListener('input', () => {
      if (searchInput.value.trim().length > 0) searchSuggestions.classList.add('active');
      });
      document.addEventListener('click', (e) => {
      if (!e.target.closest('.search-wrapper')) searchSuggestions.classList.remove('active');
      });
      searchSuggestions.addEventListener('click', (e) => {
      const item = e.target.closest('.suggestion-item');
      if (item) {
      searchInput.value = item.textContent.trim();
      searchSuggestions.classList.remove('active');
      }
      });

      // ========== القائمة الضخمة (hover) ==========
      const megaMenuItem = document.getElementById('megaMenuItem');
      megaMenuItem.addEventListener('mouseenter', () => megaMenuItem.classList.add('active'));
      megaMenuItem.addEventListener('mouseleave', () => megaMenuItem.classList.remove('active'));

      // ========== حساب المستخدم (Dropdown) ==========
      const accountDropdown = document.getElementById('accountDropdown');
      const accountBtn = document.getElementById('accountBtn');
      accountBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      accountDropdown.classList.toggle('active');
      });
      document.addEventListener('click', (e) => {
      if (!accountDropdown.contains(e.target)) accountDropdown.classList.remove('active');
      });

      // ========== السلة (يمكن ربطها بدرج أو صفحة) ==========
      document.getElementById('cartToggle').addEventListener('click', () => {
      alert('فتح سلة المشتريات (يمكن استبدالها بدرج جانبي)');
      });

      // ========== الجوال ==========
      const overlay = document.getElementById('overlay');
      const mobileDrawer = document.getElementById('mobileDrawer');
      const mobileMenuToggle = document.getElementById('mobileMenuToggle');
      const mobileDrawerClose = document.getElementById('mobileDrawerClose');
      const mobileSearchToggle = document.getElementById('mobileSearchToggle');
      const searchWrapper = document.getElementById('searchWrapper');

      function openMobileDrawer() {
      mobileDrawer.classList.add('open');
      overlay.classList.add('active');
      document.body.style.overflow = 'hidden';
      }
      function closeMobileDrawer() {
      mobileDrawer.classList.remove('open');
      overlay.classList.remove('active');
      document.body.style.overflow = '';
      }

      mobileMenuToggle.addEventListener('click', openMobileDrawer);
      mobileDrawerClose.addEventListener('click', closeMobileDrawer);
      overlay.addEventListener('click', () => {
      closeMobileDrawer();
      accountDropdown.classList.remove('active');
      });

      mobileSearchToggle.addEventListener('click', () => {
      searchWrapper.classList.toggle('mobile-visible');
      });

      // ========== تحسين الوصول ==========
      document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
      closeMobileDrawer();
      searchSuggestions.classList.remove('active');
      accountDropdown.classList.remove('active');
      }
      });

      console.log('✅ Header احترافي جاهز - جميع الميزات مفعلة');