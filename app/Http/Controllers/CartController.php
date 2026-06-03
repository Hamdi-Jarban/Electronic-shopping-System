<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Dom\RandomError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    //
    private function getOrCreateCart(Request $request)
    {
        if(Auth::check())
            {
                return Cart::firstOrCreate([
                    'user_id'=>Auth::id()
                ]);
            }
            else{
                $sessionID = $request->session()->getId();
            return Cart::firstOrCreate([
                'session_id' => $sessionID
            ]);
            }
    }
    public function index(Request $request)  {
        $cart =$this->getOrCreateCart($request);
        $cartitem = $cart::items()->with(['productVariant.product'])->get();

        $cartSummary = $cartitem::where('cart_id',$cart->cart_id)
        ->select(
            DB::raw('SUM(quantity) as total_quantity')
        )->first();
        return view('shop.cart', compact('cartItems', 'cart', 'cartSummary'));
    }
    public function add(Request $request, $variantID)  {
        $quantity = $request->input('quantity',1);
        $cart = $this->getOrCreateCart($request);

        $cartitem = $cart::items()->where('product_variant_id',$variantID)->first();
        if($cartitem)
            {
                $cartitem->increment('quantity',$quantity);
            }
            else{
                $cart->items()->create([
                'product_variant_id' => $variantID,
                'quantity' => $quantity
                ]);
            }
        $totalItemsCount = DB::table('cart_items')
            ->where('cart_id', $cart->cart_id)
            ->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المنتج للسلة بنجاح',
            'count' => $totalItemsCount
        ]);
    }
    public function update(Request $request, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::findOrFail($itemId);
        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الكمية بنجاح'
        ]);
    }
    public function remove($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المنتج من السلة'
        ]);
    }
}
