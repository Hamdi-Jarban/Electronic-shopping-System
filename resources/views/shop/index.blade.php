@extends('layouts.customer')

@section('title', 'تصفح المنتجات')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

  <aside class="space-y-6">
    <form action="{{ route('shop.index') }}" method="GET" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
      <h3 class="font-semibold mb-3 text-gray-700">البحث السريع</h3>
      <div class="relative">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن منتج..." class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-emerald-500">
        <button type="submit" class="absolute left-2 top-2 text-gray-400 hover:text-emerald-600">🔍</button>
      </div>
    </form>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
      <h3 class="font-semibold mb-3 text-gray-700 border-b pb-2">أقسام السوبرماركت</h3>
      <ul class="space-y-3 text-sm">
        <li>
          <a href="{{ route('shop.index') }}" class="text-gray-600 hover:text-emerald-600 {{ !request('category') ? 'font-bold text-emerald-600' : '' }}">كل الأقسام</a>
        </li>
        @foreach($categories as $parent)
        <li class="space-y-1">
          <a href="{{ route('shop.index', ['category' => $parent->category_id]) }}" class="font-medium text-gray-800 hover:text-emerald-600 transition {{ request('category') == $parent->category_id ? 'text-emerald-600 font-bold' : '' }}">
            📁 {{ $parent->name }}
          </a>
          @if($parent->children->count() > 0)
          <ul class="mr-4 pr-2 border-r border-gray-100 space-y-1 text-xs text-gray-500">
            @foreach($parent->children as $child)
            <li>
              <a href="{{ route('shop.index', ['category' => $child->category_id]) }}" class="hover:text-emerald-600 transition {{ request('category') == $child->category_id ? 'text-emerald-600 font-bold' : '' }}">
                📄 {{ $child->name }}
              </a>
            </li>
            @endforeach
          </ul>
          @endif
        </li>
        @endforeach
      </ul>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
      <h3 class="font-semibold mb-3 text-gray-700 border-b pb-2">العلامات التجارية</h3>
      <div class="flex flex-wrap gap-2">
        @foreach($brands as $brand)
        <a href="{{ route('shop.index', ['brand' => $brand->brand_id]) }}" class="px-3 py-1.5 text-xs bg-gray-50 hover:bg-emerald-50 rounded-lg border border-gray-100 transition {{ request('brand') == $brand->brand_id ? 'bg-emerald-50 border-emerald-500 text-emerald-600 font-medium' : 'text-gray-600' }}">
          {{ $brand->name }}
        </a>
        @endforeach
      </div>
    </div>
  </aside>

  <section class="lg:col-span-3 space-y-6">
    @if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      @foreach($products as $product)
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col justify-between">
        <div class="p-4 space-y-2">
          <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-medium">
            {{ $product->brand->name ?? 'ماركة عامة' }}
          </span>
          <h2 class="font-semibold text-gray-800 hover:text-emerald-600 text-base">
            {{ $product->name }}
          </h2>
          <p class="text-xs text-gray-400 line-clamp-2">
            {{ $product->description ?? 'لا يوجد وصف حالي للمنتج.' }}
          </p>
        </div>

        <div class="p-4 bg-gray-50 border-t border-gray-50">
          @if($product->variants->count() > 0)
          @php $defaultVariant = $product->variants->first(); @endphp
          <div class="flex justify-between items-center mb-4">
            <span class="text-emerald-600 font-bold text-lg">
              {{ $defaultVariant->price }} ريال
            </span>
            <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded border border-gray-100">
              {{ $defaultVariant->sku }}
            </span>
          </div>

          <form onsubmit="addToCartAsync(event, this)" action="{{ route('cart.add', $defaultVariant->variant_id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-emerald-600 text-white text-sm py-2 rounded-lg font-medium hover:bg-emerald-700 transition shadow-sm flex items-center justify-center space-x-2 space-x-reverse">
              <span>🛒</span>
              <span>إضافة للسلة</span>
            </button>
          </form>
          @else
          <span class="text-xs text-red-500 font-medium block text-center py-2">غير متوفر حالياً</span>
          @endif
        </div>
      </div>
      @endforeach
    </div>

    <div class="mt-8">
      {{ $products->appends(request()->query())->links() }}
    </div>
    @else
    <div class="bg-white p-12 rounded-xl text-center shadow-sm border border-gray-100">
      <span class="text-4xl">📦</span>
      <p class="text-gray-500 mt-2 text-sm">
        عذراً، لا توجد منتجات تطابق خيارات البحث الحالية.
      </p>
      <a href="{{ route('shop.index') }}" class="text-emerald-600 text-sm mt-4 inline-block hover:underline">إعادة تعيين الفلترة</a>
    </div>
    @endif
  </section>

</div>


<script>
  function addToCartAsync(event, form) {
    event.preventDefault(); // منع المتصفح من فتح صفحة جديدة البيضاء

    fetch(form.action, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
    if(data.success) {
    // 1. تحديث رقم العداد في القائمة العلوية فوراً!
    // تأكد من إضافة id="cart-count" لعنصر العداد في الـ Navbar
    const badge = document.getElementById('cart-count');
    if(badge) badge.innerText = data.count;

    // 2. إظهار تنبيه لطيف للمستخدم
    alert('رائع هندسة! ' + 'تم إضافة المنتج بنجاح.');
    }
    })
    .catch(error => console.error('Error:', error));
  }
</script>

@endsection