<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
public function placeOrder(Request $request)
{
// 1. التحقق من بيانات العميل القادمة من الفورم
$request->validate([
'customer_name'    => 'required|string|max:255',
'customer_phone'   => 'required|string',
'shipping_address' => 'required|string',
]);

// 2. جلب سلة المستخدم الحالية (بناءً على حساب المسجل أو الـ Session للزائر)
if (Auth::check()) {
$cart = Cart::where('user_id', Auth::id())->first();
} else {
$sessionId = $request->session()->getId();
$cart = Cart::where('session_id', $sessionId)->first();
}

// إذا كانت السلة فارغة أو غير موجودة، نوقف العملية فوراً
if (!$cart || $cart->items->count() === 0) {
return response()->json(['message' => 'سلتك فارغة حالياً لا يمكن إتمام الطلب'], 400);
}

// 3. بدء الـ Transaction الآمن لمنع تضارب البيانات
return DB::transaction(function () use ($request, $cart) {

// حساب الإجمالي الكلي من عناصر السلة الحالية
$totalPrice = 0;
foreach ($cart->items as $cartItem) {
// نفترض أن علاقة المنتج موجودة في موديل الـ CartItem باسم product
$totalPrice += $cartItem->quantity * $cartItem->product->price;
}

// 4. إنشاء سطر الطلب الرئيسي
$order = Order::create([
'user_id'          => Auth::id(), // سيكون null تلقائياً إذا كان زائراً
'customer_name'    => $request->customer_name,
'customer_phone'   => $request->customer_phone,
'shipping_address' => $request->shipping_address,
'total_price'      => $totalPrice,
'status'           => 'pending'
]);

// 5. نقل العناصر من السلة وجدول الـ cart_item إلى جدول order_items
foreach ($cart->items as $cartItem) {
OrderItem::create([
'order_id'          => $order->order_id,
'product_id'        => $cartItem->product_id,
'quantity'          => $cartItem->quantity,
'price_at_purchase' => $cartItem->product->price // تجميد السعر للأبد!
]);
}

// 6. النتيجة الذهبية التي خططنا لها: حذف السلة وعناصرها فوراً لتهيئة المتصفح لسلة جديدة
$cart->delete();

return response()->json([
'message'  => 'تم معالجة الطلب وتفريغ السلة بنجاح!',
'order_id' => $order->order_id
], 201);
});
}
}