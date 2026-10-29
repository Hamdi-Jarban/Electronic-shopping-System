@extends('layouts.master')
@section('title', 'سلة التسوق')
@section('content')
@php
    $subtotal = 0;
    foreach ($items as $cartItem) {
        if ($cartItem->product_available && $cartItem->available_quantity >= $cartItem->quantity) {
            $subtotal += $cartItem->quantity * (float) $cartItem->variant->price;
        }
    }
@endphp
<main class="shop-page cart-page" dir="rtl">
    <div class="shop-container">
        <header class="shop-heading">
            <div><p class="shop-eyebrow">مراجعة مشترياتك</p><h1>سلة التسوق</h1><p>تأكد من توفر المنتجات والكميات قبل إتمام الطلب.</p></div>
            <a class="shop-cart-link" href="{{ route('index') }}">متابعة التسوق</a>
        </header>
        @if (session('error'))<p class="shop-notice shop-notice--error" role="alert">{{ session('error') }}</p>@endif
        @if (session('success'))<p class="shop-notice" role="status">{{ session('success') }}</p>@endif
        @if ($errors->any())<div class="shop-notice shop-notice--error" role="alert">{{ $errors->first() }}</div>@endif

        <div id="cart-empty" class="shop-empty" @if($items->isNotEmpty()) hidden @endif>
            <span class="cart-empty-icon" aria-hidden="true">🛒</span>
            <h2>سلتك فارغة حاليًا</h2>
            <p>أضف المنتجات التي ترغب بها، وستظهر هنا مع حالة توفرها.</p>
            <a href="{{ route('index') }}" class="shop-button shop-button--primary">استكشاف المنتجات</a>
        </div>

        <div id="cart-content" class="cart-layout" @if($items->isEmpty()) hidden @endif>
            <section class="cart-items" aria-label="المنتجات في السلة">
                @foreach ($items as $item)
                    @php
                        $product = $item->variant?->product;
                        $unitPrice = (float) ($item->variant?->price ?? 0);
                        $lineTotal = $unitPrice * $item->quantity;
                        $hasStock = $item->product_available && $item->available_quantity > 0;
                        $itemValid = $item->product_available && $item->available_quantity >= $item->quantity;
                        $subtotalForItem = $itemValid ? $lineTotal : 0;
                        $image = $product?->featureImage;
                        $imageUrl = $image ? asset('storage/' . $image->image_path) : 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=240&h=240&fit=crop';
                    @endphp
                    <article class="cart-product {{ $itemValid ? '' : 'cart-product--warning' }}" data-cart-row data-item-id="{{ $item->id }}" data-unit-price="{{ $unitPrice }}" data-available-quantity="{{ $item->available_quantity }}" data-current-quantity="{{ $item->quantity }}" data-product-available="{{ $item->product_available ? '1' : '0' }}">
                        <a class="cart-product__image" href="{{ $product?->is_active ? route('products.show', $product->slug) : route('index') }}"><img src="{{ $imageUrl }}" alt="{{ $product?->name ?? 'منتج' }}" loading="lazy"></a>
                        <div class="cart-product__main">
                            <div class="cart-product__heading">
                                <div><h2><a href="{{ $product?->is_active ? route('products.show', $product->slug) : route('index') }}">{{ $product?->name ?? 'منتج لم يعد متاحًا' }}</a></h2><p class="cart-product__sku">رمز المنتج: {{ $item->variant?->sku ?? '—' }}</p></div>
                                <button class="cart-remove" type="button" data-remove-trigger aria-label="إزالة {{ $product?->name ?? 'المنتج' }} من السلة">إزالة</button>
                            </div>
                            @php
                                $attributes = $item->variant?->attributes ?? [];
                                $attributeLabel = collect($attributes)->map(fn ($value, $key) => $key . ': ' . $value)->implode('، ');
                            @endphp
                            @if ($attributeLabel)<p class="cart-product__variant">{{ $attributeLabel }}</p>@endif
                            <p class="cart-stock {{ $itemValid ? 'is-available' : 'is-unavailable' }}" data-stock-status role="status">
                                @if (!$item->product_available)
                                    هذا المنتج غير متاح للبيع حاليًا. أزله من السلة للمتابعة.
                                @elseif ($item->available_quantity < 1)
                                    نفد المخزون. يمكنك إزالة المنتج أو العودة لاحقًا.
                                @elseif ($item->quantity > $item->available_quantity)
                                    المتاح الآن {{ $item->available_quantity }} فقط؛ خفّض الكمية أو أزل المنتج.
                                @else
                                    متوفر — {{ $item->available_quantity }} قطعة متاحة
                                @endif
                            </p>
                            <div class="cart-product__bottom">
                                <form class="cart-quantity-form" action="{{ route('cart.update', $item) }}" method="POST" data-cart-update-form>
                                    @csrf @method('PATCH')
                                    <label for="quantity-{{ $item->id }}">الكمية</label>
                                    <div class="cart-quantity-control">
                                        <input id="quantity-{{ $item->id }}" type="number" name="quantity" min="1" max="{{ max(1, min(99, $item->available_quantity)) }}" value="{{ $item->quantity }}" inputmode="numeric" required {{ !$hasStock || !$item->product_available ? 'disabled' : '' }}>
                                        <button class="shop-button shop-button--secondary" type="submit" {{ !$hasStock || !$item->product_available ? 'disabled' : '' }}>تحديث</button>
                                    </div>
                                </form>
                                <div class="cart-product__price"><span>{{ number_format($unitPrice, 2) }} ر.س للقطعة</span><strong data-line-total>{{ number_format($subtotalForItem, 2) }} ر.س</strong></div>
                            </div>
                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="cart-remove-form" data-cart-remove-form hidden>@csrf @method('DELETE')</form>
                            <p class="cart-feedback" data-cart-feedback role="status" aria-live="polite"></p>
                        </div>
                    </article>
                @endforeach
            </section>

            <aside class="cart-summary" aria-label="ملخص السلة">
                <h2>ملخص الطلب</h2>
                <div class="cart-summary__row"><span>قيمة المنتجات المتاحة</span><strong id="cart-subtotal" data-subtotal="{{ $subtotal }}">{{ number_format($subtotal, 2) }} ر.س</strong></div>
                <p class="cart-summary__note">لا تُحتسب المنتجات غير المتوفرة أو التي تجاوزت الكمية المتاحة ضمن الإجمالي.</p>
                @if ($hasUnavailableItems)
                    <p id="cart-checkout-warning" class="cart-checkout-warning" role="alert">حدّث الكميات أو أزل المنتجات غير المتوفرة لتفعيل إتمام الطلب.</p>
                @else
                    <p id="cart-checkout-warning" class="cart-checkout-warning" role="alert" hidden>حدّث الكميات أو أزل المنتجات غير المتوفرة لتفعيل إتمام الطلب.</p>
                @endif
                <a id="cart-checkout" class="shop-button shop-button--primary {{ $hasUnavailableItems ? 'is-disabled' : '' }}" href="{{ $hasUnavailableItems ? '#' : route('checkout.index') }}" @if($hasUnavailableItems) aria-disabled="true" tabindex="-1" @endif>متابعة إلى إتمام الطلب</a>
                <a href="{{ route('index') }}" class="cart-continue">متابعة التسوق</a>
            </aside>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const rows = () => [...document.querySelectorAll('[data-cart-row]')];
    const checkout = document.getElementById('cart-checkout');
    const warning = document.getElementById('cart-checkout-warning');
    const summary = document.getElementById('cart-subtotal');
    const feedback = (row, message, isError = false) => {
        const element = row?.querySelector('[data-cart-feedback]');
        if (!element) return;
        element.textContent = message;
        element.classList.toggle('is-error', isError);
    };
    const updateSummary = () => {
        let subtotal = 0;
        let invalid = false;
        rows().forEach((row) => {
            const quantity = Number(row.dataset.currentQuantity || 0);
            const available = Number(row.dataset.availableQuantity || 0);
            const active = row.dataset.productAvailable === '1';
            const valid = active && quantity > 0 && quantity <= available;
            if (valid) subtotal += Number(row.dataset.unitPrice || 0) * quantity;
            else invalid = true;
            row.classList.toggle('cart-product--warning', !valid);
            const line = row.querySelector('[data-line-total]');
            if (line) line.textContent = `${(valid ? Number(row.dataset.unitPrice) * quantity : 0).toFixed(2)} ر.س`;
            const quantityInput = row.querySelector('input[name="quantity"]');
            const updateButton = row.querySelector('[data-cart-update-form] button[type="submit"]');
            if (quantityInput) quantityInput.disabled = !active || available < 1;
            if (updateButton) updateButton.disabled = !active || available < 1;
            const status = row.querySelector('[data-stock-status]');
            if (status && active) {
                status.textContent = available < 1 ? 'نفد المخزون. يمكنك إزالة المنتج أو العودة لاحقًا.' : (valid ? `متوفر — ${available} قطعة متاحة` : `المتاح الآن ${available} فقط؛ خفّض الكمية أو أزل المنتج.`);
                status.classList.toggle('is-available', valid);
                status.classList.toggle('is-unavailable', !valid);
            }
        });
        if (summary) {
            summary.textContent = `${subtotal.toFixed(2)} ر.س`;
            summary.dataset.subtotal = subtotal;
        }
        if (checkout) {
            checkout.classList.toggle('is-disabled', invalid || rows().length === 0);
            checkout.setAttribute('aria-disabled', invalid || rows().length === 0 ? 'true' : 'false');
            checkout.href = invalid || rows().length === 0 ? '#' : @json(route('checkout.index'));
            checkout.tabIndex = invalid || rows().length === 0 ? -1 : 0;
        }
        if (warning) warning.hidden = !invalid;
        const empty = rows().length === 0;
        document.getElementById('cart-empty').hidden = !empty;
        document.getElementById('cart-content').hidden = empty;
    };

    document.querySelectorAll('[data-cart-update-form], [data-cart-remove-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const row = form.closest('[data-cart-row]');
            const button = event.submitter || form.querySelector('button[type="submit"]');
            const originalText = button?.textContent;
            if (button) { button.disabled = true; button.textContent = 'جارٍ الحفظ…'; }
            feedback(row, '');
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const validationMessage = data.errors ? Object.values(data.errors).flat()[0] : null;
                    const error = new Error(validationMessage || data.message || 'تعذر تحديث السلة. حاول مرة أخرى.');
                    error.payload = data;
                    throw error;
                }
                if (form.matches('[data-cart-remove-form]')) {
                    row.remove();
                } else {
                    row.dataset.currentQuantity = data.quantity;
                    if (data.available_quantity !== undefined) row.dataset.availableQuantity = data.available_quantity;
                    const quantityInput = row.querySelector('input[name="quantity"]');
                    if (quantityInput) {
                        quantityInput.value = data.quantity;
                        quantityInput.max = Math.min(99, Math.max(1, Number(row.dataset.availableQuantity)));
                    }
                    feedback(row, data.message || 'تم تحديث الكمية.');
                }
                document.querySelectorAll('#cart-count, .cart-count').forEach((counter) => counter.textContent = data.cart_count ?? 0);
                updateSummary();
            } catch (error) {
                const input = row.querySelector('input[name="quantity"]');
                if (error.payload?.available_quantity !== undefined) {
                    row.dataset.availableQuantity = error.payload.available_quantity;
                    if (input) input.max = Math.min(99, Math.max(1, Number(error.payload.available_quantity)));
                }
                if (input) input.value = row.dataset.currentQuantity;
                updateSummary();
                feedback(row, error.message, true);
            } finally {
                if (button) { button.disabled = false; button.textContent = originalText; }
            }
        });
    });

    document.querySelectorAll('[data-remove-trigger]').forEach((button) => {
        button.addEventListener('click', () => {
            button.closest('[data-cart-row]')?.querySelector('[data-cart-remove-form]')?.requestSubmit();
        });
    });
});
</script>
@endpush
