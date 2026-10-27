<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private function sessionToken(): string
    {
        if (!Session::has('cart_session_token')) {
            Session::put('cart_session_token', (string) Str::uuid());
        }
        return (string) Session::get('cart_session_token');
    }

    public function index()
    {
        $items = $this->ownedItems()->with('variant.product.featureImage')->get();
        return view('cart.index', compact('items'));
    }

    public function checkout()
    {
        $items = $this->ownedItems()->with('variant.product')->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة.');
        }
        return view('checkout.index', compact('items'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:99'],
        ]);
        $quantity = (int) ($data['quantity'] ?? 1);
        $userId = Auth::id();
        $token = $userId ? null : $this->sessionToken();

        if ($userId && Session::has('cart_session_token')) {
            $this->mergeGuestCart($userId, (string) Session::get('cart_session_token'));
            Session::forget('cart_session_token');
        }

        $variant = ProductVariant::with('product')->findOrFail($data['variant_id']);
        abort_unless($variant->product->is_active, 404);

        DB::transaction(function () use ($variant, $quantity, $userId, $token) {
            $available = (int) DB::table('warehouse_inventory')
                ->where('variant_id', $variant->id)
                ->selectRaw('COALESCE(SUM(physical_qty - reserved_qty), 0) as available')
                ->value('available');
            if ($available < 1) {
                abort(422, 'هذا المنتج غير متوفر في المخزون.');
            }

            $query = CartItem::where('variant_id', $variant->id)->lockForUpdate();
            $userId ? $query->where('user_id', $userId) : $query->where('session_token', $token);
            $item = $query->first();
            $newQuantity = ($item?->quantity ?? 0) + $quantity;
            if ($newQuantity > $available) {
                abort(422, 'الكمية المطلوبة تتجاوز المخزون المتاح ('.$available.').');
            }
            if ($item) {
                $item->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create(['user_id' => $userId, 'session_token' => $token, 'variant_id' => $variant->id, 'quantity' => $quantity]);
            }
        });
        return response()->json(['success' => true, 'message' => 'تمت الإضافة إلى السلة.', 'cart_count' => $this->ownedItems()->sum('quantity')]);
    }

    private function ownedItems()
    {
        return Auth::check()
            ? CartItem::where('user_id', Auth::id())
            : CartItem::where('session_token', $this->sessionToken());
    }

    private function mergeGuestCart(int $userId, string $token): void
    {
        DB::transaction(function () use ($userId, $token) {
            $guestItems = CartItem::where('session_token', $token)->lockForUpdate()->get();
            foreach ($guestItems as $guestItem) {
                $userItem = CartItem::where('user_id', $userId)->where('variant_id', $guestItem->variant_id)->lockForUpdate()->first();
                if ($userItem) {
                    $userItem->increment('quantity', $guestItem->quantity);
                    $guestItem->delete();
                } else {
                    $guestItem->update(['user_id' => $userId, 'session_token' => null]);
                }
            }
        });
    }
}
