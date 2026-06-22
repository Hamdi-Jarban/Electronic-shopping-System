<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CartItem;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // مشاركة عداد السلة تلقائياً مع الهيكل الرئيسي في كل الصفحات
        View::composer('*', function ($view) {
            $cartCount = 0;
            
            // تحقق إذا كان العميل مسجل دخول أو لديه جلسة زائر مجهزة
            if (auth()->check() || session()->has('_token')) {
                $cartCount = CartItem::where('user_id', auth()->id())
                    ->orWhere('session_token', session()->getId())
                    ->sum('quantity');
            }

            $view->with('cartCount', $cartCount);
        });
    }
}