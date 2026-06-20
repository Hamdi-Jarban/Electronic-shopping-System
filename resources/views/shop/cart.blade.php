@extends('layouts.customer')

@section('content')
@vite('resources/css/Cart.css');

<div class="cart-page">
  <div class="cart-header fade-in">
    <h1 class="cart-header-title">سلة التسوق</h1>
    <p class="cart-header-subtitle">
      أنت على بُعد خطوات من إكمال طلبك
    </p>
  </div>

  {{-- حالة السلة الفارغة (تظهر عند عدم وجود عناصر) --}}
  <div class="empty-cart fade-in" id="emptyCart" style="display: none;">
    <div class="empty-cart-icon">
      <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
      </svg>
    </div>
    <h2 class="empty-cart-title">سلتك فارغة</h2>
    <p class="empty-cart-text">
      لم تقم بإضافة أي منتجات بعد. استكشف متجرنا وابدأ التسوق الآن.
    </p>
    <a href="/products" class="btn btn-primary" style="text-decoration: none;">تصفح المنتجات</a>
  </div>

  {{-- محتوى السلة الممتلئة --}}
  <div class="cart-content fade-in" id="cartContent">
    <div class="cart-items-section" id="cartItemsContainer">
      {{-- عناصر السلة تدرج هنا ديناميكياً أو ثابتة --}}
    </div>

    <aside class="summary-section">
      <h2 class="summary-title">ملخص الطلب</h2>
      <div class="summary-row">
        <span>عدد المنتجات</span>
        <span id="itemCount">3</span>
      </div>
      <div class="summary-row">
        <span>المجموع الفرعي</span>
        <span id="subtotal">449.97 ر.س</span>
      </div>
      <div class="summary-row">
        <span>تكلفة الشحن</span>
        <span id="shipping">30.00 ر.س</span>
      </div>
      <div class="summary-row discount" id="discountRow" style="display: none;">
        <span>الخصم</span>
        <span id="discountAmount">- 0.00 ر.س</span>
      </div>
      <div class="coupon-section">
        <input type="text" class="coupon-input" placeholder="كود الخصم" id="couponInput" aria-label="كود الخصم">
        <button class="coupon-btn" id="applyCoupon">تطبيق</button>
      </div>
      <div class="summary-row total">
        <span>الإجمالي النهائي</span>
        <span id="finalTotal">479.97 ر.س</span>
      </div>
      <div class="action-buttons">
        <a href="/checkout" class="btn btn-primary">إتمام الطلب</a>
        <a href="/products" class="btn btn-outline">متابعة التسوق</a>
      </div>
    </aside>
  </div>
</div>

<script>
  (function() {
  // بيانات وهمية للمنتجات في السلة
  const cartItemsData = [
  {
  id: 1,
  name: 'حقيبة يد جلدية فاخرة',
  description: 'جلد طبيعي 100%، تصميم أنيق يناسب جميع المناسبات',
  price: 249.99,
  quantity: 1,
  image: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"%3E%3Crect width="100" height="100" fill="%23f3f4f6"/%3E%3Cpath d="M30 40 L50 25 L70 40 L65 75 L35 75 Z" fill="%23d1d5db" stroke="%239ca3af" stroke-width="2"/%3E%3Ccircle cx="50" cy="50" r="8" fill="%23e5e7eb" stroke="%239ca3af" stroke-width="2"/%3E%3C/svg%3E'
  },
  {
  id: 2,
  name: 'سماعات لاسلكية احترافية',
  description: 'عزل ضوضاء ممتاز، عمر بطارية 30 ساعة',
  price: 159.99,
  quantity: 2,
  image: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"%3E%3Crect width="100" height="100" fill="%23f3f4f6"/%3E%3Cellipse cx="50" cy="45" rx="20" ry="22" fill="%23d1d5db" stroke="%239ca3af" stroke-width="2"/%3E%3Crect x="30" y="55" width="40" height="15" rx="5" fill="%23e5e7eb" stroke="%239ca3af" stroke-width="2"/%3E%3C/svg%3E'
  },
  {
  id: 3,
  name: 'ساعة ذكية رياضية',
  description: 'تتبع النشاط، مراقبة النوم، مقاومة للماء',
  price: 349.99,
  quantity: 1,
  image: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"%3E%3Crect width="100" height="100" fill="%23f3f4f6"/%3E%3Crect x="35" y="25" width="30" height="45" rx="10" fill="%23d1d5db" stroke="%239ca3af" stroke-width="2"/%3E%3Ccircle cx="50" cy="47" r="10" fill="%23e5e7eb" stroke="%239ca3af" stroke-width="2"/%3E%3C/svg%3E'
  }
  ];

  let cartItems = [...cartItemsData];
  let discountPercent = 0;
  const validCoupons = {
  'WELCOME10': 10,
  'SAVE20': 20,
  'VIP30': 30
  };

  const cartContainer = document.getElementById('cartItemsContainer');
  const emptyCartEl = document.getElementById('emptyCart');
  const cartContentEl = document.getElementById('cartContent');
  const itemCountEl = document.getElementById('itemCount');
  const subtotalEl = document.getElementById('subtotal');
  const shippingEl = document.getElementById('shipping');
  const discountRow = document.getElementById('discountRow');
  const discountAmountEl = document.getElementById('discountAmount');
  const finalTotalEl = document.getElementById('finalTotal');
  const couponInput = document.getElementById('couponInput');
  const applyCouponBtn = document.getElementById('applyCoupon');

  function formatCurrency(amount) {
  return amount.toFixed(2) + ' ر.س';
  }

  function renderCart() {
  if (cartItems.length === 0) {
  cartContentEl.style.display = 'none';
  emptyCartEl.style.display = 'flex';
  return;
  }

  cartContentEl.style.display = 'grid';
  emptyCartEl.style.display = 'none';
  cartContainer.innerHTML = '';

  cartItems.forEach((item, index) => {
  const itemTotal = item.price * item.quantity;
  const cartItemEl = document.createElement('div');
  cartItemEl.className = 'cart-item fade-in';
  cartItemEl.style.animationDelay = `${index * 0.08}s`;
  cartItemEl.innerHTML = `
  <div class="cart-item-image">
  <img src="${item.image}" alt="${item.name}" loading="lazy">
  </div>
  <div class="cart-item-details">
  <div class="cart-item-name">${item.name}</div>
  <div class="cart-item-description">${item.description}</div>
  <div class="cart-item-price">${formatCurrency(item.price)}</div>
  </div>
  <div class="cart-item-actions">
  <div class="quantity-control">
  <button class="quantity-btn minus" data-id="${item.id}" aria-label="تقليل الكمية">−</button>
  <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="99" data-id="${item.id}" aria-label="الكمية">
  <button class="quantity-btn plus" data-id="${item.id}" aria-label="زيادة الكمية">+</button>
  </div>
  <span class="cart-item-total">${formatCurrency(itemTotal)}</span>
  <button class="remove-btn" data-id="${item.id}" aria-label="حذف المنتج">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
  <polyline points="3 6 5 6 21 6"></polyline>
  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
  <path d="M10 11v6"></path>
  <path d="M14 11v6"></path>
  </svg>
  </button>
  </div>
  `;
  cartContainer.appendChild(cartItemEl);
  });

  updateSummary();
  attachEventListeners();
  }

  function updateSummary() {
  const itemCount = cartItems.reduce((sum, item) => sum + item.quantity, 0);
  const subtotal = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  const shipping = subtotal > 300 ? 0 : 30.00;
  const discountAmount = subtotal * (discountPercent / 100);
  const finalTotal = subtotal - discountAmount + shipping;

  itemCountEl.textContent = itemCount;
  subtotalEl.textContent = formatCurrency(subtotal);
  shippingEl.textContent = shipping === 0 ? 'مجاني' : formatCurrency(shipping);

  if (discountPercent > 0) {
  discountRow.style.display = 'flex';
  discountAmountEl.textContent = `- ${formatCurrency(discountAmount)}`;
  } else {
  discountRow.style.display = 'none';
  }

  finalTotalEl.textContent = formatCurrency(finalTotal);
  }

  function attachEventListeners() {
  document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
  btn.addEventListener('click', function() {
  const id = parseInt(this.dataset.id);
  const item = cartItems.find(item => item.id === id);
  if (item && item.quantity > 1) {
  item.quantity--;
  renderCart();
  }
  });
  });

  document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
  btn.addEventListener('click', function() {
  const id = parseInt(this.dataset.id);
  const item = cartItems.find(item => item.id === id);
  if (item && item.quantity < 99) {
  item.quantity++;
  renderCart();
  }
  });
  });

  document.querySelectorAll('.quantity-input').forEach(input => {
  input.addEventListener('change', function() {
  const id = parseInt(this.dataset.id);
  const item = cartItems.find(item => item.id === id);
  let newQty = parseInt(this.value);
  if (isNaN(newQty) || newQty < 1) newQty = 1;
  if (newQty > 99) newQty = 99;
  if (item) {
  item.quantity = newQty;
  renderCart();
  }
  });
  });

  document.querySelectorAll('.remove-btn').forEach(btn => {
  btn.addEventListener('click', function() {
  const id = parseInt(this.dataset.id);
  const cartItemEl = this.closest('.cart-item');
  cartItemEl.classList.add('removing');
  cartItemEl.addEventListener('animationend', function() {
  cartItems = cartItems.filter(item => item.id !== id);
  renderCart();
  }, { once: true });
  });
  });
  }

  applyCouponBtn.addEventListener('click', function() {
  const code = couponInput.value.trim().toUpperCase();
  if (validCoupons.hasOwnProperty(code)) {
  discountPercent = validCoupons[code];
  couponInput.style.borderColor = 'var(--success)';
  couponInput.style.backgroundColor = 'rgba(16, 185, 129, 0.04)';
  setTimeout(() => {
  couponInput.style.borderColor = 'var(--border)';
  couponInput.style.backgroundColor = 'var(--surface-secondary)';
  }, 1500);
  } else {
  discountPercent = 0;
  couponInput.style.borderColor = 'var(--danger)';
  couponInput.style.backgroundColor = 'rgba(239, 68, 68, 0.04)';
  setTimeout(() => {
  couponInput.style.borderColor = 'var(--border)';
  couponInput.style.backgroundColor = 'var(--surface-secondary)';
  }, 1500);
  alert('كود الخصم غير صالح');
  }
  updateSummary();
  });

  // تحسين تجربة الضغط على Enter في حقل الكوبون
  couponInput.addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
  e.preventDefault();
  applyCouponBtn.click();
  }
  });

  // التصيير الأولي
  renderCart();
  })();
</script>
@endsection