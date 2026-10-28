@extends('layouts.master')

@section('title', 'إدارة الطلبات')

@section('content')
<style>
    .orders-admin { max-width: 1180px; margin: 32px auto; padding: 0 16px; font-family: inherit; }
    .orders-admin h1 { margin-bottom: 18px; }
    .orders-notice { padding: 12px 16px; margin-bottom: 16px; border-radius: 8px; background: #e8f7ed; color: #155724; }
    .orders-error { padding: 12px 16px; margin-bottom: 16px; border-radius: 8px; background: #fdeaea; color: #842029; }
    .orders-table-wrap { overflow-x: auto; background: #fff; border-radius: 10px; }
    .orders-table { width: 100%; border-collapse: collapse; min-width: 760px; }
    .orders-table th, .orders-table td { padding: 12px; border-bottom: 1px solid #e7e9ee; text-align: right; vertical-align: top; }
    .orders-table th { background: #f6f7f9; }
    .order-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .order-actions button { border: 0; border-radius: 6px; padding: 8px 12px; cursor: pointer; color: #fff; }
    .order-fulfill { background: #16794b; }
    .order-cancel { background: #b42318; }
    .orders-empty { padding: 28px; text-align: center; color: #5b6472; }
    .orders-pagination { padding: 16px; }
</style>

<main class="orders-admin">
    <h1>إدارة الطلبات</h1>
    @if (session('status'))
        <div class="orders-notice" role="status">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="orders-error" role="alert">{{ $errors->first() }}</div>
    @endif

    <div class="orders-table-wrap">
        @if ($orders->isEmpty())
            <div class="orders-empty">لا توجد طلبات حتى الآن.</div>
        @else
            <table class="orders-table">
                <thead>
                    <tr><th>رقم الطلب</th><th>العميل</th><th>المنتجات</th><th>الإجمالي</th><th>الحالة</th><th>الإجراءات</th></tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name ?: ($order->user?->name ?? '—') }}<br><small>{{ $order->customer_phone ?? '' }}</small></td>
                            <td>
                                @foreach ($order->items as $item)
                                    <div>{{ $item->variant?->product?->name ?? 'منتج محذوف' }} × {{ $item->quantity }}</div>
                                @endforeach
                            </td>
                            <td>{{ number_format((float) $order->net_amount, 2) }}</td>
                            <td>{{ $order->status }}</td>
                            <td>
                                @if ($order->status === 'pending')
                                    <div class="order-actions">
                                        <form method="POST" action="{{ route('admin.orders.fulfill', $order) }}" onsubmit="return confirm('اعتماد الطلب وخصم المنتجات من المخزون؟')">
                                            @csrf
                                            <button class="order-fulfill" type="submit">اعتماد الطلب</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" onsubmit="return confirm('إلغاء الطلب وتحرير المخزون المحجوز؟')">
                                            @csrf
                                            <button class="order-cancel" type="submit">إلغاء الطلب</button>
                                        </form>
                                    </div>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="orders-pagination">{{ $orders->links() }}</div>
        @endif
    </div>
</main>
@endsection
