@extends('layouts.master')
@section('title', 'سلة التسوق')
@section('content')
<main class="container" dir="rtl">
    <h1>سلة التسوق</h1>
    @if (session('error'))<p role="alert">{{ session('error') }}</p>@endif
    @if (session('success'))<p class="shop-notice" role="status">{{ session('success') }}</p>@endif
    @if ($items->isEmpty())
        <p>سلتك فارغة حالياً.</p><a href="{{ route('index') }}">متابعة التسوق</a>
    @else
        @php($total = 0)
        @foreach ($items as $item)
            @php($line = $item->quantity * $item->variant->price)
            @php($total += $line)
            <article class="cart-item"><h2>{{ $item->variant->product->name }}</h2>
                <p>رمز: {{ $item->variant->sku }} | الكمية: {{ $item->quantity }}</p>
                <p>{{ number_format($item->variant->price, 2) }} × {{ $item->quantity }} = {{ number_format($line, 2) }}</p>
            </article>
        @endforeach
        <p><strong>الإجمالي: {{ number_format($total, 2) }}</strong></p>
        <a href="{{ route('checkout.index') }}">إتمام الطلب</a>
    @endif
</main>
@endsection
