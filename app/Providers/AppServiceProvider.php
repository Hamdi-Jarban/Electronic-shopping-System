<?php

namespace App\Providers;

use App\Models\CartItem;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('manage-products', fn ($user) => $user->roles()->where('name', 'admin')->exists());

        View::composer('*', function ($view) {
            $query = CartItem::query();
            if (auth()->check()) {
                $query->where('user_id', auth()->id());
            } elseif (session()->has('cart_session_token')) {
                $query->where('session_token', session('cart_session_token'));
            } else {
                $query->whereRaw('1 = 0');
            }
            $view->with('cartCount', (int) $query->sum('quantity'));
        });
    }
}
