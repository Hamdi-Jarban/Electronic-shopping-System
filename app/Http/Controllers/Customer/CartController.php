<?php

namespace App\Http\Controllers\Customer;

use App\Models\Cart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
  private function getSessionToken()
  {
    if (!Session::has('cart_session_token')) {
      Session::put('cart_session_token', Str::uuid()->toString());
    }
    return Session::get('cart_session_token');
  }
  public function add(Request $request)
  {
    $request->validate([
      'variant_id' => 'required|exists:product_variants,id',
      'quantity' => 'integer|min:1'
    ]);

    $variantId = $request->variant_id;
    $quantity = $request->input('quantity', 1);
    $userId = Auth::id();

    if ($userId && Session::has('cart_session_token')) {
      $sessionToken = Session::get('cart_session_token');

      DB::transaction(function () use ($userId, $sessionToken) {
        $guestItems = CartItem::where('session_token', $sessionToken)->get();
        foreach ($guestItems as $guestItem) {
          $userItem = CartItem::where('user_id', $userId)
            ->where('variant_id', $guestItem->variant_id)
            ->first();

          if ($userItem) {
            $userItem->increment('quantity', $guestItem->quantity);
            $guestItem->delete();
          } else {
            $guestItem->update(['user_id' => $userId, 'session_token' => null]);
          }
        }
        Session::forget('cart_session_token');
      });
    }

    $sessionToken = $userId ? null : $this->getSessionToken();

    DB::transaction(function () use ($variantId, $quantity, $userId, $sessionToken) {
      $cartItem = CartItem::where('variant_id', $variantId)
        ->when($userId, function ($query) use ($userId) {
          return $query->where('user_id', $userId);
        })
        ->when(!$userId, function ($query) use ($sessionToken) {
          return $query->where('session_token', $sessionToken);
        })
        ->lockForUpdate()
        ->first();

      if ($cartItem) {
        $cartItem->increment('quantity', $quantity);
      } else {
        CartItem::create([
          'user_id' => $userId,
          'session_token' => $sessionToken,
          'variant_id' => $variantId,
          'quantity' => $quantity
        ]);
      }
    });

    return response()->json(['success' => true, 'message' => 'ok increment']);
  }
}
