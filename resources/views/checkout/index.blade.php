@extends('layouts.master')
@section('title', 'إتمام الطلب')
@section('content')
<div class="checkout-container" dir="rtl">
    <h1>إتمام الطلب</h1>
    @if ($errors->any())<div role="alert">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('orders.store') }}">
        @csrf
        <section class="card">
            <h2>بيانات العميل والتوصيل</h2>
            <label>الاسم الكامل<input required name="customer_name" maxlength="255" value="{{ old('customer_name', auth()->user()->name ?? '') }}"></label>
            <label>رقم الهاتف<input required name="customer_phone" maxlength="30" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"></label>
            <label>عنوان التوصيل<textarea required name="shipping_address" maxlength="2000">{{ old('shipping_address') }}</textarea></label>
            <label>ملاحظات<textarea name="notes" maxlength="2000">{{ old('notes') }}</textarea></label>
        </section>
        <section class="card">
            <h2>ملخص الطلب</h2>
            @php($total = 0)
            @foreach ($items as $item)
                @php($lineTotal = $item->quantity * $item->variant->price)
                @php($total += $lineTotal)
                <p>{{ $item->variant->product->name }} — {{ $item->quantity }} × {{ number_format($item->variant->price, 2) }} = {{ number_format($lineTotal, 2) }}</p>
            @endforeach
            <strong>الإجمالي: {{ number_format($total, 2) }}</strong>
        </section>
        <button type="submit">تأكيد الطلب (الدفع عند الاستلام)</button>
    </form>
</div>
@endsection
