<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderHeader;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\ProductVariant;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $variants = ProductVariant::all();

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        for ($i = 1; $i <= 200; $i++) {
            $customer = $customers->random();
            $totalAmount = 0;

            $order = OrderHeader::create([
                'user_id' => $customer->user_id,
                'order_date' => now()->subDays(rand(0, 180)),
                'total_amount' => 0,
                'order_status' => $statuses[array_rand($statuses)],
                'shipping_address' => $customer->default_address ?? 'عنوان افتراضي',
                'notes' => rand(0, 1) ? 'يرجى التوصيل في الفترة المسائية' : null,
            ]);

            // 1-5 عناصر
            $orderVariants = $variants->random(rand(1, 5));
            foreach ($orderVariants as $variant) {
                $quantity = rand(1, 3);
                $unitPrice = $variant->price;
                $totalAmount += $quantity * $unitPrice;

                OrderItem::create([
                    'order_id' => $order->order_id,
                    'variant_id' => $variant->variant_id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                ]);
            }

            $order->update(['total_amount' => round($totalAmount, 2)]);
        }

        $this->command->info('✓ تم إنشاء ' . OrderHeader::count() . ' طلب');
        $this->command->info('✓ تم إنشاء ' . OrderItem::count() . ' عنصر طلب');
    }
}
