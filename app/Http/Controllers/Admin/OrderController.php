<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderInventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['items.variant.product'])
            ->latest('created_at')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function cancel(Order $order, OrderInventoryService $inventory): RedirectResponse
    {
        $inventory->cancel($order->id);

        return back()->with('status', 'تم إلغاء الطلب وتحرير الكمية المحجوزة.');
    }

    public function fulfill(Request $request, Order $order, OrderInventoryService $inventory): RedirectResponse
    {
        $inventory->fulfill($order->id, $request->user()?->id);

        return back()->with('status', 'تم اعتماد الطلب وخصم الكمية من المخزون.');
    }
}
