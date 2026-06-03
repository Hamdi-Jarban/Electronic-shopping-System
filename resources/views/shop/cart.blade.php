@extends('layouts.customer')

@section('title', 'سلة المشتريات')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">🛒 سلة المشتريات الحالية</h1>

    @if($cartItems->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="divide-y divide-gray-150">
                @foreach($cartItems as $item)
                    <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 text-lg">
                                {{ $item->productVariant->product->name ?? 'منتج غير معروف' }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-1">
                                الحجم/الوزن: <span class="text-gray-600 font-medium">{{ $item->productVariant->sku }}</span>
                            </p>
                            <p class="text-sm text-emerald-600 font-medium mt-1">
                                سعر الوحدة: {{ $item->productVariant->price }} ريال
                            </p>
                        </div>

                        <div class="flex items-center space-x-3 space-x-reverse">
                            <span class="text-sm text-gray-500">الكمية:</span>
                            <div class="flex items-center border border-gray-200 rounded-lg bg-gray-50">
                                <form action="{{ route('cart.update', $item->cart_item_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                    <button type="submit" class="px-3 py-1 text-gray-600 hover:bg-gray-200 rounded-r-lg" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                </form>

                                <span class="px-4 py-1 text-sm font-semibold text-gray-800 bg-white border-x border-gray-200">
                                    {{ $item->quantity }}
                                </span>

                                <form action="{{ route('cart.update', $item->cart_item_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                    <button type="submit" class="px-3 py-1 text-gray-600 hover:bg-gray-200 rounded-l-lg">+</button>
                                </form>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 space-x-reverse justify-between w-full sm:w-auto">
                            <div class="text-left sm:text-right">
                                <span class="block text-xs text-gray-400">الإجمالي</span>
                                <span class="font-bold text-gray-800">
                                    {{ $item->quantity * ($item->productVariant->price ?? 0) }} ريال
                                </span>
                            </div>

                            <form action="{{ route('cart.remove', $item->cart_item_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                    🗑️
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="bg-gray-50 p-6 border-t border-gray-150 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-right">
                    <p class="text-sm text-gray-500">إجمالي قطع السلة: <span class="font-semibold text-gray-700">{{ $totalQuantity }}</span></p>
                    <p class="text-xl font-bold text-gray-800 mt-1">المبلغ الإجمالي المستحق: <span class="text-emerald-600">{{ $totalPrice }} ريال</span></p>
                </div>

                <div class="w-full sm:w-auto">
                    @auth
                        <a href="#" class="block w-full text-center bg-emerald-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-emerald-700 transition shadow-sm">
                            المتابعة لإتمام الدفع والشراء 💳
                        </a>
                    @endauth

                    @guest
                        <div class="flex flex-col gap-2">
                            <a href="#" class="block w-full text-center bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-sm">
                                تسجيل الدخول لإنهاء الشراء 🔑
                            </a>
                            <a href="#" class="block w-full text-center bg-gray-200 text-gray-700 px-6 py-2 rounded-xl text-sm font-medium hover:bg-gray-300 transition">
                                المتابعة كـ ضيف سريع بدون حساب
                            </a>
                        </div>
                    @endguest
                </div>
            </div>

        </div>
    @else
        <div class="bg-white p-12 rounded-xl text-center shadow-sm border border-gray-100">
            <span class="text-6xl">🛒</span>
            <p class="text-gray-500 mt-4 text-base">سلتك فارغة حالياً! تصفح المتجر وأضف بعض المنتجات.</p>
            <a href="{{ route('shop.index') }}" class="mt-6 inline-block bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                العودة إلى المتجر
            </a>
        </div>
    @endif
</div>
@endsection
