<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [];
        for ($i = 0; $i < 50; $i++) {
            $type = fake()->randomElement(['fixed', 'percentage']);
            $value = $type === 'fixed' ? fake()->randomFloat(2, 5, 200) : fake()->randomFloat(2, 5, 50);
            $coupons[] = [
                'code' => strtoupper(fake()->unique()->lexify('??????')),
                'type' => $type,
                'value' => $value,
                'start_date' => fake()->optional(0.5)->dateTimeBetween('-1 month', 'now'),
                'end_date' => fake()->optional(0.8)->dateTimeBetween('now', '+2 months'),
                'usage_limit' => fake()->optional(0.7)->numberBetween(10, 200),
                'used_count' => 0,
            ];
        }
        DB::table('coupons')->insert($coupons);
    }
}