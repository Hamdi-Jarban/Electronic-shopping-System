<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    private array $usedOrderNumbers = [];
    private array $usedTrackingNumbers = [];
    private array $usedTransactionIds = [];
    private array $usedReturnNumbers = [];

    private const CARRIERS = ['ارامكس', 'دي اتش ال', 'فاستلو', 'البريد السعودي', 'زاجل', 'ناقل'];
    private const GATEWAYS = ['بطاقة ائتمانية', 'مدى', 'تحويل بنكي', 'Apple Pay', 'STC Pay'];

    public function run(): void
    {
    fake()->locale('ar_SA');
        $this->command->info('🚀 بدء إنشاء الطلبات...');

        // ─── جلب البيانات المرجعية ─────────────────────────────
        $userIds      = DB::table('users')->pluck('id')->toArray();
        $couponIds    = DB::table('coupons')->pluck('id')->toArray();
        $warehouseIds = DB::table('warehouses')->where('is_active', true)->pluck('id')->toArray();
        $variants     = DB::table('product_variants')->select('id', 'price')->get();

        if (empty($userIds) || $variants->isEmpty() || empty($warehouseIds)) {
            $this->command->error('❌ يجب وجود مستخدمين، متغيرات منتجات، ومستودعات نشطة أولاً!');
            return;
        }

        $variantsArr = $variants->toArray();
        $totalOrders = 2000;

        // ─── إنشاء الطلبات دفعة واحدة ─────────────────────────
        $this->command->info("📦 إنشاء $totalOrders طلب...");
        $ordersBatch = [];

        for ($i = 0; $i < $totalOrders; $i++) {
            $ordersBatch[] = [
                'user_id'        => $userIds[array_rand($userIds)],
                'coupon_id'      => fake()->boolean(20) && !empty($couponIds) ? $couponIds[array_rand($couponIds)] : null,
                'order_number'   => $this->generateUnique(fake()->bothify('ORD-??###??'), 'usedOrderNumbers'),
                'status'         => 'pending',
                'total_amount'   => 0,
                'discount_amount' => 0,
                'net_amount'      => 0,
                'created_at'     => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s'),
            ];
        }

        DB::table('orders')->insert($ordersBatch);
        $this->command->info('✅ الطلبات الأساسية جاهزة.');

        // ─── معالجة كل طلب على حدة (تجنب مشاكل الذاكرة) ──────
        $orders = DB::table('orders')->orderBy('id')->get();
        $total   = $orders->count();
        $current = 0;

        foreach ($orders as $order) {
            $current++;
            if ($current % 200 === 0) {
                $this->command->info("⏳ معالجة $current / $total");
            }

            // 1. عناصر الطلب
            $itemCount = rand(1, 4);
            $totalAmount = 0;
            $items = [];

            for ($j = 0; $j < $itemCount; $j++) {
                $variant = $variantsArr[array_rand($variantsArr)];
                $qty = rand(1, 3);
                $totalAmount += $variant->price * $qty;

                $items[] = [
                    'order_id'   => $order->id,
                    'variant_id' => $variant->id,
                    'quantity'   => $qty,
                    'price'      => $variant->price,
                ];
            }
            DB::table('order_items')->insert($items);

            // 2. الخصم
            $discount = 0;
            if ($order->coupon_id) {
                $coupon = DB::table('coupons')->find($order->coupon_id);
                if ($coupon) {
                    $discount = $coupon->type === 'fixed'
                        ? min((float)$coupon->value, $totalAmount)
                        : round($totalAmount * ((float)$coupon->value / 100), 2);

                    DB::table('coupon_usages')->insert([
                        'coupon_id'         => $order->coupon_id,
                        'user_id'           => $order->user_id,
                        'order_id'          => $order->id,
                        'discount_obtained' => $discount,
                        'used_at'           => now()->toDateTimeString(),
                    ]);

                    DB::table('coupons')->where('id', $order->coupon_id)->increment('used_count');
                }
            }

            $netAmount = round($totalAmount - $discount, 2);
            $status    = $this->pickStatus();

            // 3. تحديث الطلب
            DB::table('orders')->where('id', $order->id)->update([
                'total_amount'   => $totalAmount,
                'discount_amount' => $discount,
                'net_amount'      => $netAmount,
                'status'          => $status,
            ]);

            // 4. الدفع
            DB::table('payments')->insert([
                'order_id'       => $order->id,
                'gateway'        => fake()->randomElement(self::GATEWAYS),
                'transaction_id' => $this->generateUnique(fake()->bothify('TXN-??####??'), 'usedTransactionIds'),
                'status'         => $status === 'cancelled' ? fake()->randomElement(['failed', 'refunded']) : 'completed',
                'amount'         => $netAmount,
                'created_at'     => $order->created_at,
            ]);

            // 5. سجل الحالة
            DB::table('order_histories')->insert([
                'order_id'   => $order->id,
                'changed_by' => $order->user_id,
                'old_status' => 'pending',
                'new_status' => $status,
                'comment'    => $status === 'cancelled' ? fake()->randomElement(['طلب العميل الإلغاء', 'فشل الدفع']) : 'تم تأكيد الطلب',
                'created_at' => now()->toDateTimeString(),
            ]);

            // 6. الشحن (للطلبات غير الملغية)
            if (!in_array($status, ['cancelled', 'pending'])) {
                DB::table('shipments')->insert([
                    'order_id'        => $order->id,
                    'warehouse_id'    => $warehouseIds[array_rand($warehouseIds)],
                    'carrier_name'    => fake()->randomElement(self::CARRIERS),
                    'tracking_number' => $this->generateUnique(fake()->bothify('TRK-??####??'), 'usedTrackingNumbers'),
                    'status'          => $status === 'delivered' ? 'delivered' : fake()->randomElement(['in_transit', 'out_for_delivery']),
                    'shipping_cost'   => fake()->randomFloat(2, 5, 50),
                    'shipping_price'  => fake()->randomFloat(2, 15, 70),
                    'shipped_at'      => fake()->dateTimeBetween('-2 weeks', '-1 day')->format('Y-m-d H:i:s'),
                    'delivered_at'    => $status === 'delivered' ? fake()->dateTimeBetween('-1 week', 'now')->format('Y-m-d H:i:s') : null,
                    'created_at'      => now()->toDateTimeString(),
                    'updated_at'      => now()->toDateTimeString(),
                ]);
            }

            // 7. مرتجع (8% احتمال)
            if (fake()->boolean(8) && in_array($status, ['delivered', 'shipped'])) {
                $returnId = DB::table('order_returns')->insertGetId([
                    'order_id'      => $order->id,
                    'user_id'       => $order->user_id,
                    'return_number' => $this->generateUnique(fake()->bothify('RET-??####??'), 'usedReturnNumbers'),
                    'status'        => fake()->randomElement(['requested', 'approved', 'received']),
                    'refund_amount' => round($netAmount * fake()->randomFloat(2, 0.5, 0.9), 2),
                    'reason'        => fake()->randomElement(['منتج تالف', 'مقاس غير مناسب', 'مخالف للوصف', 'تغير الرأي']),
                    'created_at'    => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
                    'updated_at'    => now()->toDateTimeString(),
                ]);

                // عناصر المرتجع
                $orderItems = DB::table('order_items')->where('order_id', $order->id)->limit(2)->get();
                foreach ($orderItems as $item) {
                    DB::table('order_return_items')->insert([
                        'order_return_id' => $returnId,
                        'variant_id'      => $item->variant_id,
                        'quantity'        => min(1, $item->quantity),
                        'condition'       => fake()->randomElement(['good', 'opened', 'damaged']),
                    ]);
                }
            }
        }

        $this->command->info('✅ تم إنشاء جميع الطلبات والبيانات المرتبطة بنجاح!');
        $this->command->info("📊 المجموع: $totalOrders طلب");
    }

    /**
     * توليد قيمة فريدة وضمان عدم تكرارها.
     */
    private function generateUnique(string $value, string $property): string
    {
        while (isset($this->{$property}[$value])) {
            $value = fake()->bothify('??###??');
        }
        $this->{$property}[$value] = true;
        return $value;
    }

    /**
     * اختيار حالة طلب باحتمالات واقعية.
     */
    private function pickStatus(): string
    {
        $rand = mt_rand(1, 100);
        return match (true) {
            $rand <= 15  => 'pending',
            $rand <= 40  => 'processing',
            $rand <= 70  => 'shipped',
            $rand <= 95  => 'delivered',
            default      => 'cancelled',
        };
    }
}