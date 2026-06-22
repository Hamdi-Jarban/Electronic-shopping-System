<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CouponUsageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'coupon_id' => null,
            'user_id' => null,
            'order_id' => null,
            'discount_obtained' => 0,
            'used_at' => now(),
        ];
    }
}