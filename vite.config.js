import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/Admin/Product/Create.css',
                'resources/css/checkout/Main.css',
                'resources/css/checkout/master.css',
                'resources/css/shop.css',
                'resources/css/customer/customer.css',
                'resources/css/customer/product/cart.css',
                'resources/js/app.js',
                'resources/js/customer.js',
                'resources/js/customer/main.js',
                'resources/js/lucide.min.js',
                'resources/js/master.js',
                'resources/js/shop-cart.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: { ignored: ['**/vendor/**', '**/node_modules/**'] },
    },
});
