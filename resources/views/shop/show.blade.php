@extends('layouts.customer')

@section('content')
<div class="product-details-container" style="max-width: 1200px; margin: 40px auto; padding: 20px; direction: rtl; font-family: sans-serif;">
    
    {{-- مسار التتبع (Breadcrumbs) --}}
    <nav style="margin-bottom: 20px; color: #666; font-size: 14px;">
        <a href="/shop" style="color: var(--color-accent, #222); text-decoration: none;">المتجر</a> / 
        <span>{{ $product->categories->first()?->name ?? 'عام' }}</span> / 
        <span style="color: #999;">{{ $product->name }}</span>
    </nav>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: start;">
        
        {{-- القسم الأيمن: معرض صور المنتج --}}
        <div class="product-gallery">
            <div style="background: #f9f9f9; padding: 20px; border-radius: 12px; border: 1px solid #eee; text-align: center;">
                <img id="mainProductImage" src="{{ $product->variants->first()?->image_url ? asset('storage/'.$product->variants->first()->image_url) : asset('images/products/default.jpg') }}" 
                     alt="{{ $product->name }}" style="max-width: 100%; height: auto; border-radius: 8px;">
            </div>
            
            {{-- صور المتغيرات الأخرى إن وجدت --}}
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                @foreach($product->variants as $variant)
                    @if($variant->image_url)
                    <img src="{{ asset('storage/'.$variant->image_url) }}" class="thumbnail-img" 
                         style="width: 70px; height: 70px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; object-fit: cover;"
                         onclick="document.getElementById('mainProductImage').src = this.src">
                    @endif
                @endforeach
            </div>
        </div>

        {{-- القسم الأيسر: بيانات المنتج وخيارات الشراء والسحب من المستودعات --}}
        <div class="product-essential-info">
            <span style="background: #f0f0f0; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; color: #555;">
                {{ $product->brand?->name ?? 'ماركة عامة' }}
            </span>
            
            <h1 style="font-size: 32px; margin: 15px 0 10px; color: #111;">{{ $product->name }}</h1>
            
            {{-- التقييم --}}
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <div style="color: #ffb300; display: flex;">
                    @for($i = 1; $i <= 5; $i++)
                        ★
                    @endfor
                </div>
                <span style="color: #666; font-size: 14px;">({{ $product->reviews_count ?? 0 }} مراجعة من الزبائن)</span>
            </div>

            {{-- السعر --}}
            <div style="margin-bottom: 25px;">
                @php $defaultVariant = $product->variants->first(); @endphp
                <span id="productPrice" style="font-size: 28px; font-weight: bold; color: #e53935;">{{ number_format($defaultVariant?->price ?? 0) }} ر.س</span>
                @if($defaultVariant && $defaultVariant->old_price > $defaultVariant->price)
                    <span style="text-decoration: line-through; color: #aaa; margin-right: 15px; font-size: 18px;">{{ number_format($defaultVariant->old_price) }} ر.س</span>
                @endif
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 25px;">

            <p style="color: #555; line-height: 1.8; margin-bottom: 30px;">
                {{ $product->description }}
            </p>

            {{-- خيارات المتغيرات (Attributes Selection) لـ سايبر بازار --}}
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 10px;">اختر الخيار المتوفر:</label>
                <select id="variantSelector" class="form-select" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;">
                    @foreach($product->variants as $variant)
                        <option value="{{ $variant->variant_id }}" data-price="{{ $variant->price }}">
                            {{ $variant->size_option ? 'مقاس: '.$variant->size_option : '' }} 
                            {{ $variant->color_option ? ' - لون: '.$variant->color_option : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- حالة كميات المخزن الديناميكي --}}
            <div style="margin-bottom: 30px;">
                @php $totalStock = $product->variants->first()?->total_stock ?? 0; @endphp
                <span id="stockBadge" style="font-weight: bold; padding: 6px 15px; border-radius: 6px; font-size: 14px; background: {{ $totalStock > 0 ? '#e8f5e9; color: #2e7d32;' : '#ffebee; color: #c62828;' }}">
                    {{ $totalStock > 0 ? '✓ متوفر في المستودعات (المتاح '.$totalStock.' قطع)' : '✕ نفذ المخزون حالياً' }}
                </span>
            </div>

            {{-- أزرار التحكم الفوري بالسلة --}}
            <div style="display: flex; gap: 15px;">
                <div style="display: flex; border: 1px solid #ccc; border-radius: 8px; overflow: hidden;">
                    <button onclick="changeQty(-1)" style="padding: 10px 15px; background: #fff; border: 0; cursor: pointer; font-weight: bold;">-</button>
                    <input type="number" id="purchaseQty" value="1" min="1" style="width: 50px; text-align: center; border: 0; font-weight: bold;">
                    <button onclick="changeQty(1)" style="padding: 10px 15px; background: #fff; border: 0; cursor: pointer; font-weight: bold;">+</button>
                </div>
                
                <button class="add-to-cart-btn" id="mainAddToCartBtn" data-variant-id="{{ $product->variants->first()?->variant_id }}"
                        style="flex: 1; padding: 15px; background: #222; color: #fff; border: 0; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer;">
                    🛍️ أضف إلى سلة المشتريات
                </button>
            </div>

        </div>
    </div>
</div>

<script>
function changeQty(amount) {
    const qtyInput = document.getElementById('purchaseQty');
    let currentQty = parseInt(qtyInput.value) + amount;
    if (currentQty >= 1) qtyInput.value = currentQty;
}

// تحديث معرف الزر والسعر ديناميكياً عند تغيير الخيار من القائمة المنسدلة
document.getElementById('variantSelector').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    const variantId = this.value;
    
    document.getElementById('productPrice').innerText = parseFloat(price).toLocaleString() + ' ر.س';
    document.getElementById('mainAddToCartBtn').setAttribute('data-variant-id', variantId);
});
</script>
@endsection
