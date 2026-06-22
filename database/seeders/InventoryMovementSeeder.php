<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryMovementSeeder extends Seeder
{
    public function run(): void
    {fake()->locale('ar_SA');
        $warehouseIds = DB::table('warehouses')->pluck('id');
        $variantIds = DB::table('product_variants')->pluck('id');
        $userIds = DB::table('users')->pluck('id');

        $movements = [];
        $total = 5000;
        for ($i = 0; $i < $total; $i++) {
            $movements[] = [
                'warehouse_id' => $warehouseIds->random(),
                'variant_id' => $variantIds->random(),
                'user_id' => fake()->boolean(80) ? $userIds->random() : null,
                'quantity' => fake()->numberBetween(-50, 50),
                'type' => fake()->randomElement(['inbound', 'outbound', 'adjustment', 'return', 'allocation']),
                'reference_type' => 'manual',
                'reference_id' => null,
                'reason' => fake()->optional()->sentence(),
                'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            ];
        }

        foreach (array_chunk($movements, 500) as $chunk) {
            DB::table('inventory_movements')->insert($chunk);
        }
    }
}