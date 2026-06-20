 document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('mainHeader');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeMobileMenu = document.getElementById('closeMobileMenu');

    // Header scroll effect
    window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 20);
    });

    // Mobile menu
    mobileMenuBtn?.addEventListener('click', () => mobileMenu.classList.add('open'));
    closeMobileMenu?.addEventListener('click', () => mobileMenu.classList.remove('open'));
    mobileMenu?.addEventListener('click', (e) => {
    if (e.target === mobileMenu) mobileMenu.classList.remove('open');
    });

    // Close mobile menu on link click
    mobileMenu?.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => mobileMenu.classList.remove('open'));
    });
    });
document.addEventListener('DOMContentLoaded', function () {
    // 1. التقاط جميع أزرار "إضافة للسلة" في الصفحة
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // منع أي سلوك افتراضي للزر

            // 2. جلب معرف متغير المنتج المتواجد داخل الزر
            let variantId = this.getAttribute('data-variant-id');
            
            // 3. قراءة توكن الحماية CSRF من الـ Meta Tag المكتوب في الـ Header لديك
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // 4. إرسال طلب الـ AJAX المخفي إلى السيرفر
            fetch(`/cart/add/${variantId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken // تمرير التوكن لتخطي حماية لارافل
                },
                body: JSON.stringify({
                    quantity: 1 // الكمية الافتراضية عند الضغط على الزر
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // 5. السحر الحقيقي: تحديث الرقم داخل الـ Badge فوراً بدون ريفريش
                if (data.success) {
                    // استهداف العنصر <span id="cart-count"> المتواجد في كود الـ Header الخاص بك
                    const cartCountElement = document.getElementById('cart-count');
                    
                    if (cartCountElement) {
                        cartCountElement.innerText = data.count; // تبديل الرقم القديم بالعدد الجديد قادماً من السيرفر
                    }

                    // (اختياري) يمكنك إظهار رسالة تأكيد خفيفة أو تنبيه بسيط للمستخدم هنا
                    console.log(data.message); 
                }
            })
            .catch(error => {
                console.error('حدثت مشكلة أثناء إرسال البيانات:', error);
            });
        });
    });
});
