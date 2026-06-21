<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
  private function getOrCreateCart(Request $request): Cart
  {
    if (Auth::check()) {
      return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }
    return Cart::firstOrCreate(['session_id' => $request->session()->getId()]);
  }

  /**
   * حساب ملخص كامل للسلة
   */
  private function getFullSummary(Cart $cart): array
  {
    $items = $cart->items()->with('productVariant.product')->get();

    $totalQuantity = $items->sum('quantity');
    $uniqueProducts = $items->count();
    $totalPrice = $items->sum(function ($item) {
      return $item->quantity * ($item->productVariant->price ?? 0);
    });
    $totalWeight = $items->sum(function ($item) {
      return $item->quantity * ($item->productVariant->weight_kg ?? 0);
    });
    $avgPrice = $totalQuantity > 0 ? $totalPrice / $totalQuantity : 0;

    return [
      'totalQuantity' => $totalQuantity,
      'uniqueProducts' => $uniqueProducts,
      'totalPrice' => round($totalPrice, 2),
      'totalWeight' => round($totalWeight, 2),
      'avgPrice' => round($avgPrice, 2),
    ];
  }

  public function index(Request $request): View
  {
    $cart = $this->getOrCreateCart($request);
    $cartItems = $cart->items()->with('productVariant.product')->get();
    $summary = $this->getFullSummary($cart);

    return view('shop.cart', array_merge(
      compact('cart', 'cartItems'),
      $summary
    ));
  }

  public function add(Request $request, int $variantID): JsonResponse
  {
    $request->validate(['quantity' => 'sometimes|integer|min:1']);
    $quantity = $request->input('quantity', 1);
    $cart = $this->getOrCreateCart($request);

    $cartItem = $cart->items()->where('variant_id', $variantID)->first();

    if ($cartItem) {
      $cartItem->increment('quantity', $quantity);
    } else {
      $cart->items()->create([
        'variant_id' => $variantID,
        'quantity'   => $quantity,
      ]);
    }

    $summary = $this->getFullSummary($cart);

    return response()->json([
      'success' => true,
      'message' => 'تمت الإضافة بنجاح',
      'count'   => $summary['totalQuantity'],
      'summary' => $summary,
    ]);
  }

  public function update(Request $request, int $itemId): JsonResponse
  {
    $request->validate(['quantity' => 'required|integer|min:1']);

    $cart = $this->getOrCreateCart($request);
    $cartItem = $cart->items()->findOrFail($itemId);
    $cartItem->update(['quantity' => $request->quantity]);

    $summary = $this->getFullSummary($cart);

    return response()->json([
      'success' => true,
      'message' => 'تم تحديث الكمية',
      'summary' => $summary,
    ]);
  }

  public function remove(Request $request, int $itemId): JsonResponse
  {
    $cart = $this->getOrCreateCart($request);
    $cartItem = $cart->items()->findOrFail($itemId);
    $cartItem->delete();

    $summary = $this->getFullSummary($cart);

    return response()->json([
      'success' => true,
      'message' => 'تم حذف المنتج',
      'summary' => $summary,
    ]);
  }

  /**
   * إرجاع عدد عناصر السلة فقط (للتحديث السريع)
   */
  public function count(Request $request): JsonResponse
  {
    $cart = $this->getOrCreateCart($request);
    $count = $cart->items()->sum('quantity');

    return response()->json(['count' => $count]);
  }
}
