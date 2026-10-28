<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderInventoryReservation;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $userId = Auth::id();
        $token = $request->session()->get('cart_session_token');

        $order = DB::transaction(function () use ($data, $userId, $token) {
            $itemsQuery = CartItem::query()->with(['variant.product']);
            $userId ? $itemsQuery->where('user_id', $userId) : $itemsQuery->where('session_token', $token ?? '');
            $items = $itemsQuery->lockForUpdate()->get();
            if ($items->isEmpty()) {
                throw ValidationException::withMessages(['cart' => 'السلة فارغة.']);
            }

            $total = 0.0;
            $allocations = [];
            foreach ($items as $item) {
                $variant = $item->variant;
                if (!$variant || !$variant->product || !$variant->product->is_active) {
                    throw ValidationException::withMessages(['cart' => 'أحد المنتجات لم يعد متاحاً.']);
                }
                $inventories = DB::table('warehouse_inventory')->where('variant_id', $variant->id)->lockForUpdate()->get();
                $available = (int) $inventories->sum(fn ($inventory) => $inventory->physical_qty - $inventory->reserved_qty);
                if ($available < $item->quantity) {
                    throw ValidationException::withMessages(['cart' => 'المخزون غير كافٍ للمنتج: '.$variant->product->name]);
                }
                $remaining = $item->quantity;
                $allocations[$item->id] = [];
                foreach ($inventories as $inventory) {
                    $free = max(0, $inventory->physical_qty - $inventory->reserved_qty);
                    $reserve = min($free, $remaining);
                    if ($reserve > 0) {
                        DB::table('warehouse_inventory')->where('id', $inventory->id)->update(['reserved_qty' => $inventory->reserved_qty + $reserve]);
                        $allocations[$item->id][] = ['inventory_id' => $inventory->id, 'quantity' => $reserve];
                        $remaining -= $reserve;
                    }
                    if ($remaining === 0) break;
                }
                $total += (float) $variant->price * $item->quantity;
            }

            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(10)),
                'status' => 'pending',
                'total_amount' => round($total, 2),
                'discount_amount' => 0,
                'net_amount' => round($total, 2),
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'notes' => $data['notes'] ?? null,
            ]);
            foreach ($items as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->variant->price,
                ]);
                foreach ($allocations[$item->id] as $allocation) {
                    OrderInventoryReservation::create([
                        'order_item_id' => $orderItem->id,
                        'warehouse_inventory_id' => $allocation['inventory_id'],
                        'quantity' => $allocation['quantity'],
                    ]);
                }
            }
            CartItem::whereIn('id', $items->pluck('id'))->delete();
            return $order;
        });

        $request->session()->forget('cart_session_token');
        if (!$request->expectsJson()) {
            return redirect()->route('index')->with('order_number', $order->order_number);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم استلام طلبك بنجاح.',
            'order_number' => $order->order_number,
        ], 201);
    }
}
