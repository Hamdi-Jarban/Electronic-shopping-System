<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\Warehouse;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $variants = ProductVariant::all();
        $warehouses = Warehouse::all();

        foreach ($variants as $variant) {
            // كل متغير موجود في 2-4 مستودعات
            $selectedWarehouses = $warehouses->random(rand(2, 4));

            foreach ($selectedWarehouses as $warehouse) {
                Inventory::create([
                    'variant_id' => $variant->variant_id,
                    'warehouse_id' => $warehouse->warehouse_id,
                    'quantity_in_stock' => rand(0, 500),
                    'reorder_level' => 10,
                    'reorder_quantity' => 50,
                    'last_updated' => now()->subDays(rand(0, 30)),
                ]);
            }
        }

        $this->command->info('✓ تم إنشاء ' . Inventory::count() . ' سجل مخزون');
    }
}
