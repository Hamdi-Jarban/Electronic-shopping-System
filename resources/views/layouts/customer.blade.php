<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سوبرماركت المهندس - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <div class="flex-shrink-0">
                    <a href="{{ route('shop.index') }}" class="text-2xl font-bold text-emerald-600">حمدي صفت ماركت</a>
                </div>

                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-emerald-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
<span id="cart-count" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
    @php
        // جلب السلة الحالية للزائر أو المستخدم وحساب مجموع الكميات
        $currentSessionId = request()->session()->getId();
        $cartCount = \App\Models\Cart::where('user_id', auth()->id())
                        ->orWhere('session_id', $currentSessionId)
                        ->first();
    @endphp
    {{ $cartCount ? $cartCount->items()->sum('quantity') : 0 }}
</span>

                        </span>
                    </a>

                    @auth
                        <span class="text-sm text-gray-600">مرحباً، {{ auth()->user()->full_name }}</span>
                        <a href="#" class="text-sm text-red-500 hover:underline">خروج</a>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                            تسجيل الدخول
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 mt-12 py-6 text-center text-sm text-gray-500">
        جميع الحقوق محفوظة &copy; {{ date('Y') }} - شركة حمدي صفت للنظم.
    </footer>

</body>
</html>
