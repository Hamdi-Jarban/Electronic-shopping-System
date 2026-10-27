@extends('layouts.master')
@section('title', 'إدارة المنتجات')
@section('content')
<main class="container" dir="rtl">
    <h1>إدارة المنتجات</h1>
    <p><a href="{{ route('admin.products.create') }}">إضافة منتج جديد</a></p>
    @if (session('status'))<p role="status">{{ session('status') }}</p>@endif
    <table>
        <thead><tr><th>المنتج</th><th>العلامة التجارية</th><th>السعر</th><th>الحالة</th></tr></thead>
        <tbody>
        @forelse ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->brand?->name ?? '—' }}</td>
                <td>{{ $product->defaultVariant ? number_format($product->defaultVariant->price, 2) : '—' }}</td>
                <td>{{ $product->is_active ? 'نشط' : 'غير نشط' }}</td>
            </tr>
        @empty
            <tr><td colspan="4">لا توجد منتجات بعد.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $products->links() }}
</main>
@endsection
