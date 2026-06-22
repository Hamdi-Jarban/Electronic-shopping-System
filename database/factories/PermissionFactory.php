<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => null,
            'gateway' => fake()->randomElement(['بطاقة ائتمانية', 'مدى', 'تحويل بنكي']),
            'transaction_id' => strtoupper(fake()->unique()->lexify('TXN??????')),
            'status' => 'completed',
            'amount' => 0, // يملأ لاحقاً
            'created_at' => now(),
        ];
    }
}