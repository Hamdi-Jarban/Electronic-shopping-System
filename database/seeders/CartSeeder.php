<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🛒 بدء إنشاء عناصر السلة...');

        $userIds    = DB::table('users')->pluck('id')->toArray();
        $variantIds = DB::table('product_variants')->pluck('id')->toArray();

        if (empty($variantIds)) {
            $this->command->warn('❌ لا توجد متغيرات منتجات!');
            return;
        }

        $totalItems = 3000;
        $cartItems  = [];
        $uniqueKeys = [];

        for ($i = 0; $i < $totalItems; $i++) {
            $useUser      = fake()->boolean(70);
            $userId       = $useUser ? fake()->randomElement($userIds) : null;
            $sessionToken = !$useUser ? Str::random(40) : null;
            $variantId    = fake()->randomElement($variantIds);

            // مفتاح التفرد
            $key = $useUser ? "u_{$userId}_{$variantId}" : "s_{$sessionToken}_{$variantId}";

            // تجربة متغير آخر إذا تكرر
            $attempts = 0;
            while (isset($uniqueKeys[$key]) && $attempts < 30) {
                $variantId = fake()->randomElement($variantIds);
                $key = $useUser ? "u_{$userId}_{$variantId}" : "s_{$sessionToken}_{$variantId}";
                $attempts++;
            }

            if (isset($uniqueKeys[$key])) {
                continue;
            }

            $uniqueKeys[$key] = true;

            $cartItems[] = [
                'user_id'       => $userId,
                'session_token' => $sessionToken,
                'variant_id'    => $variantId,
                'quantity'      => rand(1, 5),
                'created_at'    => now()->toDateTimeString(),
                'updated_at'    => now()->toDateTimeString(),
            ];
        }

        // إدخال دفعات
        $inserted = 0;
        foreach (array_chunk($cartItems, 500) as $chunk) {
            DB::table('cart_items')->insert($chunk);
            $inserted += count($chunk);
        }

        $this->command->info("✅ تم إنشاء $inserted عنصر في السلة بنجاح!");
    }
}