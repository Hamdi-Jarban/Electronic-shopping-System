<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\ProductVariant;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all()->random(15);

        foreach ($customers as $customer) {
            $cart = Cart::create([
                'user_id' => $customer->user_id,
                'created_at' => now(),
            ]);

            foreach (ProductVariant::all()->random(rand(1, 4)) as $variant) {
                CartItem::create([
                    'cart_id' => $cart->cart_id,
                    'variant_id' => $variant->variant_id,
                    'quantity' => rand(1, 3),
                ]);
            }
        }
    }
}
