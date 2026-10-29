<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Adds demo opening stock only for variants that have no inventory record.
 * Existing physical and reserved quantities are never overwritten.
 */
class WarehouseInventorySeeder extends Seeder
{
    private const DEMO_OPENING_QUANTITY = 10;

    public function run(): void
    {
        $warehouseId = DB::table('warehouses')
            ->where('is_active', true)
            ->orderBy('id')
            ->value('id');

        if (!$warehouseId) {
            $this->command?->warn('لم يُنشأ مخزون تجريبي: لا يوجد مستودع نشط.');
            return;
        }

        $created = 0;
        DB::table('product_variants as variants')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('warehouse_inventory as inventory')
                    ->whereColumn('inventory.variant_id', 'variants.id');
            })
            ->orderBy('variants.id')
            ->chunkById(500, function ($variants) use ($warehouseId, &$created) {
                $now = now();
                $inventoryRows = [];
                foreach ($variants as $variant) {
                    $inventoryRows[] = [
                        'warehouse_id' => $warehouseId,
                        'variant_id' => $variant->id,
                        'physical_qty' => self::DEMO_OPENING_QUANTITY,
                        'reserved_qty' => 0,
                        'low_stock_threshold' => 3,
                        'updated_at' => $now,
                    ];
                }

                if ($inventoryRows === []) {
                    return;
                }

                DB::table('warehouse_inventory')->insertOrIgnore($inventoryRows);
                $inserted = DB::table('warehouse_inventory')
                    ->where('warehouse_id', $warehouseId)
                    ->whereIn('variant_id', array_column($inventoryRows, 'variant_id'))
                    ->where('physical_qty', self::DEMO_OPENING_QUANTITY)
                    ->where('reserved_qty', 0)
                    ->pluck('id', 'variant_id');

                $movementRows = [];
                foreach ($inserted as $variantId => $inventoryId) {
                    $alreadyLogged = DB::table('inventory_movements')
                        ->where('reference_type', 'seed_opening_balance')
                        ->where('reference_id', $inventoryId)
                        ->exists();
                    if (!$alreadyLogged) {
                        $movementRows[] = [
                            'warehouse_id' => $warehouseId,
                            'variant_id' => $variantId,
                            'user_id' => null,
                            'quantity' => self::DEMO_OPENING_QUANTITY,
                            'type' => 'adjustment',
                            'reference_type' => 'seed_opening_balance',
                            'reference_id' => $inventoryId,
                            'reason' => 'رصيد افتتاحي تجريبي للمنتج',
                            'created_at' => $now,
                        ];
                    }
                }
                if ($movementRows !== []) {
                    DB::table('inventory_movements')->insert($movementRows);
                }
                $created += count($inventoryRows);
            }, 'variants.id', 'id');

        $this->command?->info("تم إعداد المخزون التجريبي لـ {$created} متغير (10 وحدات للمتغيرات الناقصة فقط).");
    }
}
