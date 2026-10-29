document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    document.querySelectorAll('[data-cart-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const button = form.querySelector('button[type="submit"]');
            const feedback = form.querySelector('[data-cart-feedback]');
            const originalText = button?.textContent;
            if (button) {
                button.disabled = true;
                button.textContent = 'جارٍ الإضافة…';
            }
            if (feedback) {
                feedback.textContent = '';
                feedback.classList.remove('is-error');
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
                    },
                    body: new FormData(form),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const validationError = data.errors ? Object.values(data.errors).flat()[0] : null;
                    throw new Error(validationError || data.message || 'تعذرت إضافة المنتج. تحقق من المخزون وحاول مجددًا.');
                }
                if (data.cart_count !== undefined) {
                    document.querySelectorAll('#cart-count, .cart-count').forEach((counter) => {
                        counter.textContent = data.cart_count;
                    });
                }
                const message = data.message || 'تمت الإضافة إلى السلة.';
                if (feedback) {
                    feedback.textContent = message;
                } else {
                    showCartToast(message);
                }
            } catch (error) {
                if (feedback) {
                    feedback.textContent = error.message;
                    feedback.classList.add('is-error');
                } else {
                    showCartToast(error.message, true);
                }
            } finally {
                if (button) {
                    button.disabled = false;
                    button.textContent = originalText;
                }
            }
        });
    });

    function showCartToast(message, isError = false) {
        document.querySelector('.shop-toast')?.remove();
        const toast = document.createElement('div');
        toast.className = `shop-toast${isError ? ' is-error' : ''}`;
        toast.setAttribute('role', isError ? 'alert' : 'status');
        toast.textContent = message;
        document.body.appendChild(toast);
        window.setTimeout(() => toast.remove(), 3500);
    }
});
