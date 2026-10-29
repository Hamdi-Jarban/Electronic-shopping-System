<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

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
        $mergeSummary = ['adjusted' => 0, 'skipped' => 0];

        if ($userId && Session::has('cart_session_token')) {
            $mergeSummary = $this->mergeGuestCart($userId, (string) Session::get('cart_session_token'));
            Session::forget('cart_session_token');
        }

        $variant = ProductVariant::with('product')->findOrFail($data['variant_id']);
        abort_unless($variant->product->is_active, 404);

        DB::transaction(function () use ($variant, $quantity, $userId, $token) {
            $inventories = DB::table('warehouse_inventory')
                ->where('variant_id', $variant->id)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();
            $available = (int) $inventories->sum(fn ($inventory) => max(0, $inventory->physical_qty - $inventory->reserved_qty));
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
                CartItem::create([
                    'user_id' => $userId,
                    'session_token' => $token,
                    'variant_id' => $variant->id,
                    'quantity' => $quantity,
                ]);
            }
        }, 3);

        $message = $mergeSummary['adjusted'] > 0 || $mergeSummary['skipped'] > 0
            ? 'تمت الإضافة. عُدّلت بعض كميات السلة لتناسب المخزون المتاح.'
            : 'تمت الإضافة إلى السلة.';

        $cartCount = (int) $this->ownedItems()->sum('quantity');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $cartCount,
                'cart_merge' => $mergeSummary,
            ]);
        }

        return redirect()->route('cart.index')->with('success', $message);
    }

    private function ownedItems()
    {
        return Auth::check()
            ? CartItem::where('user_id', Auth::id())
            : CartItem::where('session_token', $this->sessionToken());
    }

    /** Merge guest items without letting their combined quantity exceed current stock. */
    private function mergeGuestCart(int $userId, string $token): array
    {
        return DB::transaction(function () use ($userId, $token) {
            $summary = ['merged' => 0, 'adjusted' => 0, 'skipped' => 0];
            $guestItems = CartItem::where('session_token', $token)
                ->orderBy('variant_id')
                ->lockForUpdate()
                ->get();

            foreach ($guestItems as $guestItem) {
                $variant = ProductVariant::with('product')->find($guestItem->variant_id);
                $userItem = CartItem::where('user_id', $userId)
                    ->where('variant_id', $guestItem->variant_id)
                    ->lockForUpdate()
                    ->first();

                if (!$variant || !$variant->product || !$variant->product->is_active) {
                    $guestItem->delete();
                    if ($userItem) {
                        $userItem->delete();
                    }
                    $summary['skipped']++;
                    continue;
                }

                $inventories = DB::table('warehouse_inventory')
                    ->where('variant_id', $variant->id)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();
                $available = (int) $inventories->sum(fn ($inventory) => max(0, $inventory->physical_qty - $inventory->reserved_qty));
                $requested = (int) ($userItem?->quantity ?? 0) + (int) $guestItem->quantity;
                $finalQuantity = min($requested, $available);

                if ($finalQuantity > 0) {
                    if ($userItem) {
                        $userItem->update(['quantity' => $finalQuantity]);
                    } else {
                        $guestItem->update(['user_id' => $userId, 'session_token' => null, 'quantity' => $finalQuantity]);
                    }
                    $summary['merged']++;
                } elseif ($userItem) {
                    $userItem->delete();
                    $summary['skipped']++;
                } else {
                    $summary['skipped']++;
                }

                if ($finalQuantity < $requested) {
                    $summary['adjusted']++;
                }

                if ($userItem || $finalQuantity === 0) {
                    $guestItem->delete();
                }
            }

            return $summary;
        }, 3);
    }
}
