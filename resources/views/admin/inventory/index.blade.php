@extends('layouts.master')
@section('title', 'إدارة المخزون')
@section('content')
<main class="shop-page" dir="rtl">
    <div class="shop-container">
        <header class="shop-heading">
            <div><p class="shop-eyebrow">لوحة الإدارة</p><h1>إدارة المخزون</h1><p>حدّث الكميات الفعلية في المستودعات. لا يمكن خفض الرصيد تحت الكمية المحجوزة.</p></div>
            <a class="shop-cart-link" href="{{ route('admin.products.index') }}">العودة لإدارة المنتجات</a>
        </header>
        @if (session('success'))<p class="shop-notice" role="status">{{ session('success') }}</p>@endif
        @if ($inventories->isEmpty())
            <div class="shop-empty"><h2>لا توجد سجلات مخزون</h2><p>أضف منتجًا من لوحة الإدارة أو شغّل مهيئ المخزون التجريبي بعد تجهيز المستودعات.</p></div>
        @else
            <div class="inventory-table-wrap">
                <table class="inventory-table">
                    <thead><tr><th>المنتج</th><th>الخيار / SKU</th><th>المستودع</th><th>المحجوز</th><th>المتاح</th><th>تعديل الرصيد</th></tr></thead>
                    <tbody>
                    @foreach ($inventories as $inventory)
                        <tr>
                            <td>{{ $inventory->variant?->product?->name ?? 'منتج محذوف' }}</td>
                            <td>{{ collect($inventory->variant?->attributes ?? [])->map(fn ($value, $key) => $key . ': ' . $value)->implode('، ') ?: '—' }}<small>{{ $inventory->variant?->sku }}</small></td>
                            <td>{{ $inventory->warehouse?->name ?? '—' }}</td>
                            <td>{{ $inventory->reserved_qty }}</td>
                            <td><strong>{{ max(0, $inventory->physical_qty - $inventory->reserved_qty) }}</strong></td>
                            <td>
                                <form class="inventory-update-form" action="{{ route('admin.inventory.update', $inventory) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input aria-label="الرصيد الفعلي الجديد" type="number" name="physical_qty" min="{{ $inventory->reserved_qty }}" value="{{ $inventory->physical_qty }}" required>
                                    <button class="shop-button shop-button--primary" type="submit">حفظ</button>
                                </form>
                                @error('physical_qty')<small class="inventory-error">{{ $message }}</small>@enderror
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="shop-pagination">{{ $inventories->links() }}</div>
        @endif
    </div>
</main>
@endsection
