@extends('layouts.customer')

@section('title', 'إتمام الطلب')

@section('content')
@vite('resources/css/checkout/Main.css');
<div class="checkout-container">

  <!-- شريط الخطوات -->
  <div class="steps">
    <div class="step active">
      <span class="step-num">1</span> السلة
    </div>
    <div class="step active">
      <span class="step-num">2</span> معلومات الشحن
    </div>
    <div class="step">
      <span class="step-num">3</span> الدفع
    </div>
    <div class="step">
      <span class="step-num">4</span> تأكيد الطلب
    </div>
  </div>

  <div class="checkout-grid">
    <!-- العمود الأيسر -->
    <div>
      <!-- معلومات العميل -->
      <div class="card">
        <h2 class="card-title">👤 معلومات العميل</h2>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">الاسم الكامل *</label>
            <input type="text" class="form-input" id="fullName" placeholder="مثال: أحمد محمد">
            <span class="error-msg" id="err-fullName"></span>
          </div>
          <div class="form-group">
            <label class="form-label">رقم الهاتف *</label>
            <input type="tel" class="form-input" id="phone" placeholder="مثال: 05xxxxxxxx">
            <span class="error-msg" id="err-phone"></span>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">البريد الإلكتروني</label>
          <input type="email" class="form-input" id="email" placeholder="example@email.com">
        </div>
        <div class="form-group">
          <label class="form-label">ملاحظات الطلب</label>
          <textarea class="form-textarea" id="notes" rows="2" placeholder="أي ملاحظات خاصة بطلبك..."></textarea>
        </div>
      </div>

      <!-- عنوان الشحن -->
      <div class="card">
        <h2 class="card-title">📍 عنوان الشحن</h2>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">الدولة *</label>
            <select class="form-select" id="country"><option value="">اختر الدولة</option><option value="YE">اليمن</option></select>
            <span class="error-msg" id="err-country"></span>
          </div>
          <div class="form-group">
            <label class="form-label">المحافظة *</label>
            <select class="form-select" id="governorate"><option value="">اختر المحافظة</option><option value="صنعاء">صنعاء</option></select>
            <span class="error-msg" id="err-governorate"></span>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">المدينة *</label>
            <input type="text" class="form-input" id="city" placeholder="المدينة">
            <span class="error-msg" id="err-city"></span>
          </div>
          <div class="form-group">
            <label class="form-label">الحي</label>
            <input type="text" class="form-input" id="district" placeholder="الحي">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">الشارع</label>
          <input type="text" class="form-input" id="street" placeholder="اسم الشارع">
        </div>
        <div class="form-group">
          <label class="form-label">أقرب معلم</label>
          <input type="text" class="form-input" id="landmark" placeholder="مثال: بجانب مسجد...">
        </div>
        <div class="form-group">
          <label class="form-label">العنوان التفصيلي *</label>
          <textarea class="form-textarea" id="address" rows="2" placeholder="اكتب عنوانك بالتفصيل..."></textarea>
          <span class="error-msg" id="err-address"></span>
        </div>
      </div>

      <!-- خيارات الشحن -->
      <div class="card">
        <h2 class="card-title">🚚 خيارات الشحن</h2>
        <div class="option-group" id="shipping-options">
          <label class="option-item selected">
            <input type="radio" name="shipping" value="normal" checked> شحن عادي (5-7 أيام) - <strong id="ship-normal-cost">25 ر.س</strong>
          </label>
          <label class="option-item">
            <input type="radio" name="shipping" value="express"> شحن سريع (2-3 أيام) - <strong id="ship-express-cost">45 ر.س</strong>
          </label>
          <label class="option-item">
            <input type="radio" name="shipping" value="pickup"> استلام من الفرع - <strong>مجاناً</strong>
          </label>
        </div>
      </div>

      <!-- وسائل الدفع -->
      <div class="card">
        <h2 class="card-title">💳 وسيلة الدفع</h2>
        <div class="option-group" id="payment-options">
          <label class="option-item selected">
            <input type="radio" name="payment" value="cod" checked> الدفع عند الاستلام
          </label>
          <label class="option-item">
            <input type="radio" name="payment" value="bank"> تحويل بنكي
          </label>
          <label class="option-item">
            <input type="radio" name="payment" value="wallet"> محفظة إلكترونية
          </label>
          <label class="option-item">
            <input type="radio" name="payment" value="card"> بطاقة بنكية
          </label>
        </div>
        <!-- حقول إضافية للبطاقة -->
        <div id="card-details" class="hidden" style="margin-top:1rem;">
          <div class="form-group">
            <label class="form-label">رقم البطاقة</label>
            <input type="text" class="form-input" placeholder="XXXX XXXX XXXX XXXX">
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">تاريخ الانتهاء</label>
              <input type="text" class="form-input" placeholder="MM/YY">
            </div>
            <div class="form-group">
              <label class="form-label">CVV</label>
              <input type="text" class="form-input" placeholder="***">
            </div>
          </div>
        </div>
      </div>

      <div class="card" style="display:flex; gap:1rem; flex-wrap:wrap;">
        <a href="/cart" class="btn btn-outline">⬅ العودة للسلة</a>
        <button type="button" class="btn btn-accent" id="confirmOrder" style="flex:1;">🚀 تأكيد الطلب</button>
      </div>
    </div>

    <!-- العمود الأيمن: ملخص الطلب -->
    <div style="position:sticky; top:2rem;">
      <div class="card">
        <h2 class="card-title">🧾 ملخص الطلب</h2>
        <div id="order-items">
          <!-- تُعبأ ديناميكياً أو عبر Blade -->
          <div class="summary-item">
            <img src="https://placehold.co/55x55/e2e8f0/475569?text=منتج" alt="منتج">
            <div class="summary-item-info">
              <div class="summary-item-name">
                منتج تجريبي
              </div>
              <div class="summary-item-qty">
                الكمية: 2
              </div>
            </div>
            <div class="summary-item-price">
              120 ر.س
            </div>
          </div>
          <div class="summary-item">
            <img src="https://placehold.co/55x55/e2e8f0/475569?text=منتج2" alt="منتج2">
            <div class="summary-item-info">
              <div class="summary-item-name">
                منتج آخر
              </div>
              <div class="summary-item-qty">
                الكمية: 1
              </div>
            </div>
            <div class="summary-item-price">
              75 ر.س
            </div>
          </div>
        </div>

        <div class="coupon-box">
          <input type="text" class="form-input" id="couponCode" placeholder="كود الخصم">
          <button class="btn btn-outline" id="applyCoupon">تطبيق</button>
        </div>
        <span class="success-msg hidden" id="couponMsg">تم تطبيق الخصم!</span>

        <div class="summary-row">
          <span>المجموع الفرعي</span><span id="subtotal">195 ر.س</span>
        </div>
        <div class="summary-row">
          <span>الشحن</span><span id="shippingCost">25 ر.س</span>
        </div>
        <div class="summary-row">
          <span>الخصم</span><span id="discount">0 ر.س</span>
        </div>
        <div class="summary-row summary-total">
          <span>الإجمالي</span><span id="totalPrice">220 ر.س</span>
        </div>

        <div class="secure-badges">
          <span>🔒 SSL مشفر</span>
          <span>🛡️ بيانات محمية</span>
          <span>✅ ضمان الاسترجاع</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  (function() {
  // --- العناصر ---
  const shippingRadios = document.querySelectorAll('input[name="shipping"]');
  const paymentRadios = document.querySelectorAll('input[name="payment"]');
  const cardDetails = document.getElementById('card-details');
  const shippingCostEl = document.getElementById('shippingCost');
  const subtotalEl = document.getElementById('subtotal');
  const discountEl = document.getElementById('discount');
  const totalPriceEl = document.getElementById('totalPrice');
  const applyCouponBtn = document.getElementById('applyCoupon');
  const couponCode = document.getElementById('couponCode');
  const couponMsg = document.getElementById('couponMsg');
  const confirmBtn = document.getElementById('confirmOrder');

  // القيم الافتراضية (تُستبدل ببيانات حقيقية)
  const subtotal = 195;
  let shippingCost = 25;
  let discount = 0;

  // تحديث الإجمالي
  function updateTotals() {
  const total = subtotal + shippingCost - discount;
  shippingCostEl.textContent = shippingCost + ' ر.س';
  discountEl.textContent = discount + ' ر.س';
  totalPriceEl.textContent = total + ' ر.س';
  }

  // تغيير الشحن
  shippingRadios.forEach(radio => {
  radio.addEventListener('change', function() {
  if (this.value === 'normal') shippingCost = 25;
  else if (this.value === 'express') shippingCost = 45;
  else shippingCost = 0;
  updateTotals();
  // تنسيق العنصر المحدد
  document.querySelectorAll('#shipping-options .option-item').forEach(el => el.classList.remove('selected'));
  this.closest('.option-item').classList.add('selected');
  });
  });

  // وسائل الدفع - إظهار حقل البطاقة
  paymentRadios.forEach(radio => {
  radio.addEventListener('change', function() {
  document.querySelectorAll('#payment-options .option-item').forEach(el => el.classList.remove('selected'));
  this.closest('.option-item').classList.add('selected');
  if (this.value === 'card') {
  cardDetails.classList.remove('hidden');
  } else {
  cardDetails.classList.add('hidden');
  }
  });
  });

  // كوبون الخصم
  applyCouponBtn.addEventListener('click', function() {
  const code = couponCode.value.trim();
  if (code === 'DISCOUNT10') { // مثال
  discount = 20;
  couponMsg.textContent = '✅ تم تطبيق كود الخصم بنجاح!';
  couponMsg.classList.remove('hidden');
  } else {
  discount = 0;
  couponMsg.textContent = '❌ كود الخصم غير صالح';
  couponMsg.classList.remove('hidden');
  }
  updateTotals();
  });

  // التحقق من الحقول
  function validate() {
  let valid = true;
  const fields = {
  fullName: 'الاسم الكامل مطلوب',
  phone: 'رقم الهاتف مطلوب',
  country: 'الدولة مطلوبة',
  governorate: 'المحافظة مطلوبة',
  city: 'المدينة مطلوبة',
  address: 'العنوان التفصيلي مطلوب'
  };
  // إخفاء الأخطاء القديمة
  document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
  for (const [id, msg] of Object.entries(fields)) {
  const input = document.getElementById(id);
  const errEl = document.getElementById('err-' + id);
  if (!input.value.trim()) {
  if (errEl) errEl.textContent = msg;
  valid = false;
  }
  }
  return valid;
  }

  // تأكيد الطلب
  confirmBtn.addEventListener('click', function() {
  if (!validate()) {
  alert('يرجى تعبئة الحقول المطلوبة.');
  return;
  }
  alert('تم تقديم الطلب بنجاح! شكراً لتسوقك معنا.');
  });

  // التنسيق الابتدائي للخيارات المحددة
  document.querySelectorAll('.option-item.selected input').forEach(r => r.checked = true);
  updateTotals();
  })();
</script>
@endpush