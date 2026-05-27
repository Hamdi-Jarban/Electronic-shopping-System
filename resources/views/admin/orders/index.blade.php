@extends('layouts.header')

@section('title', 'إدارة الطلبات')

@section('content')

<div class="page-header">
    <div>
        <h1>🛒 إدارة الطلبات</h1>
        <div class="breadcrumb">
            <a href="{{ url('/') }}">🏠 الرئيسية</a>
            <span>›</span>
            <span>الطلبات</span>
        </div>
    </div>
</div>

{{-- الإحصائيات --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon bg-blue">📦</div>
        <div class="stat-content">
            <span class="stat-number">{{ $stats['total'] }}</span>
            <span class="stat-label">إجمالي الطلبات</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-yellow">⏳</div>
        <div class="stat-content">
            <span class="stat-number">{{ $stats['pending'] }}</span>
            <span class="stat-label">قيد الانتظار</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-purple">🔄</div>
        <div class="stat-content">
            <span class="stat-number">{{ $stats['processing'] }}</span>
            <span class="stat-label">قيد المعالجة</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-orange">🚚</div>
        <div class="stat-content">
            <span class="stat-number">{{ $stats['shipped'] }}</span>
            <span class="stat-label">تم الشحن</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-green">✅</div>
        <div class="stat-content">
            <span class="stat-number">{{ $stats['delivered'] }}</span>
            <span class="stat-label">مكتملة</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-red">❌</div>
        <div class="stat-content">
            <span class="stat-number">{{ $stats['cancelled'] }}</span>
            <span class="stat-label">ملغية</span>
        </div>
    </div>
    <div class="stat-card revenue">
        <div class="stat-icon bg-indigo">💰</div>
        <div class="stat-content">
            <span class="stat-number">{{ number_format($stats['revenue'], 2) }} ر.س</span>
            <span class="stat-label">الإيرادات</span>
        </div>
    </div>
</div>

{{-- فلترة وبحث --}}
<div class="filter-section">
    <form action="{{ route('admin.orders.index') }}" method="GET" id="filterForm">
        <div class="filter-row">
            {{-- بحث --}}
            <div class="search-box">
                <span>🔍</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="رقم الطلب، اسم العميل...">
            </div>

         <select name="status" onchange="this.form.submit()">
    <option value="">📋 كل الحالات</option>
    <option value="قيد الانتظار" @selected(request('status') == 'قيد الانتظار')>⏳ قيد الانتظار</option>
    <option value="مؤكد" @selected(request('status') == 'مؤكد')>✅ مؤكد</option>
    <option value="معلق" @selected(request('status') == 'معلق')>🔄 معالجة</option>
    <option value="تم الشحن" @selected(request('status') == 'تم الشحن')>🚚 تم الشحن</option>
    <option value="مكتمل" @selected(request('status') == 'مكتمل')>📦 مكتمل</option>
    <option value="ملغي" @selected(request('status') == 'ملغي')>❌ ملغي</option>
</select>

            {{-- حالة الدفع --}}
            <select name="payment_status" onchange="this.form.submit()">
                <option value="">💳 كل حالات الدفع</option>
                <option value="pending" @selected(request('payment_status') == 'pending')>⏳ معلق</option>
                <option value="success" @selected(request('payment_status') == 'success')>✅ ناجح</option>
                <option value="failed" @selected(request('payment_status') == 'failed')>❌ فاشل</option>
                <option value="refunded" @selected(request('payment_status') == 'refunded')>↩️ مسترجع</option>
            </select>

            {{-- التاريخ --}}
            <div class="date-range">
                <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()">
                <span>-</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()">
            </div>

            {{-- الترتيب --}}
            <select name="sort_by" onchange="this.form.submit()">
                <option value="newest" @selected(request('sort_by', 'newest') == 'newest')>🆕 الأحدث</option>
                <option value="oldest" @selected(request('sort_by') == 'oldest')>📅 الأقدم</option>
                <option value="amount_desc" @selected(request('sort_by') == 'amount_desc')>💰 الأعلى سعراً</option>
                <option value="amount_asc" @selected(request('sort_by') == 'amount_asc')>💵 الأقل سعراً</option>
            </select>

            {{-- إعادة ضبط --}}
            <a href="{{ route('admin.orders.index') }}" class="btn-reset">🔄 إعادة ضبط</a>
        </div>
    </form>
</div>

{{-- جدول الطلبات --}}
<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>العميل</th>
                <th>التاريخ</th>
                <th>المبلغ</th>
                <th>حالة الطلب</th>
                <th>الدفع</th>
                <th>الشحن / التوصيل</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
@forelse($orders as $order)
<tr>
    <td><strong>#{{ $order->order_id }}</strong></td>
    <td>
        <div class="customer-info">
            <span class="customer-name">{{ $order->customer_name ?? 'غير معروف' }}</span>
            <span class="customer-email">{{ $order->customer_email ?? '' }}</span>
        </div>
    </td>
    <td>{{ optional($order->order_date) ? \Carbon\Carbon::parse($order->order_date)->format('Y/m/d') : '-' }}</td>
    <td>{{ number_format($order->total_amount, 2) }} ر.س</td>
    
    {{-- ✅ حالة الطلب - بالقيم العربية --}}
    <td>
        <span class="badge status-{{ $order->order_status }}">
            @switch($order->order_status)
                @case('قيد الانتظار') ⏳ قيد الانتظار @break
                @case('مؤكد') ✅ مؤكد @break
                @case('تاكيد') ✅ مؤكد @break
                @case('معلق') 🔄 معالجة @break
                @case('تم الشحن') 🚚 تم الشحن @break
                @case('مكتمل') 📦 مكتمل @break
                @case('ملغي') ❌ ملغي @break
                @default ⚪ {{ $order->order_status ?? 'غير معروف' }}
            @endswitch
        </span>
    </td>
    
    {{-- ✅ حالة الدفع - بالقيم الإنجليزية --}}
    <td>
        @if($order->payment_status)
        <span class="badge payment-{{ $order->payment_status }}">
            @switch($order->payment_status)
                @case('pending') ⏳ معلق @break
                @case('success') ✅ ناجح @break
                @case('failed') ❌ فاشل @break
                @case('refunded') ↩️ مسترجع @break
                @default ⚪ {{ $order->payment_status }}
            @endswitch
        </span>
        @else
            <span class="badge bg-gray">-</span>
        @endif
    </td>
    
    <td>
        @if($order->tracking_number)
            <span class="badge shipment-info">🚚 {{ $order->tracking_number }}</span>
        @elseif($order->delivery_status)
            <span class="badge delivery-info">🛵 {{ $order->driver_name ?? 'مندوب' }}</span>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
    <td>
        <a href="/orders/{{ $order->order_id }}" class="btn-action" title="عرض التفاصيل">👁️</a>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="empty-state">
        <div>📭 لا توجد طلبات</div>
    </td>
</tr>
@endforelse
        </tbody>
    </table>
</div>

{{-- ترقيم --}}
<div class="pagination-wrapper">
    {{ $orders->links() }}
</div>

@endsection

@push('styles')
<style>
    /* ========== الإحصائيات ========== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .stat-content {
        display: flex;
        flex-direction: column;
    }
    .stat-number {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
    }
    .stat-label {
        font-size: 12px;
        color: #64748b;
    }
    .bg-blue { background: #dbeafe; }
    .bg-yellow { background: #fef3c7; }
    .bg-purple { background: #ede9fe; }
    .bg-orange { background: #ffedd5; }
    .bg-green { background: #d1fae5; }
    .bg-red { background: #fee2e2; }
    .bg-indigo { background: #e0e7ff; }

    /* ========== الفلاتر ========== */
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .filter-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }
    .search-box {
        flex: 1;
        min-width: 200px;
        position: relative;
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0 12px;
    }
    .search-box input {
        border: none;
        background: transparent;
        padding: 10px 8px;
        width: 100%;
        outline: none;
    }
    select, .date-range input {
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        font-size: 13px;
        outline: none;
        cursor: pointer;
    }
    .date-range {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-reset {
        padding: 10px 16px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
    }

    /* ========== الجدول ========== */
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        overflow-x: auto;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    .data-table th {
        background: #f8fafc;
        padding: 14px 16px;
        text-align: right;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }
    .data-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .data-table tr:hover {
        background: #f8fafc;
    }

    .customer-info {
        display: flex;
        flex-direction: column;
    }
    .customer-name {
        font-weight: 600;
        color: #1e293b;
    }
    .customer-email {
        font-size: 12px;
        color: #64748b;
    }

    /* الشارات (Badges) */
    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-confirmed, .status-processing { background: #e0e7ff; color: #3730a3; }
    .status-shipped { background: #ffedd5; color: #9a3412; }
    .status-delivered { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }

    .payment-pending { background: #fef3c7; color: #92400e; }
    .payment-success { background: #d1fae5; color: #065f46; }
    .payment-failed { background: #fee2e2; color: #991b1b; }
    .payment-refunded { background: #e0e7ff; color: #3730a3; }

    .shipment-unknown, .delivery-unknown { background: #f1f5f9; color: #475569; }

    .btn-action {
        background: #f1f5f9;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 16px;
    }
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
    }
    .pagination-wrapper {
        margin-top: 20px;
    }
</style>
@endpush