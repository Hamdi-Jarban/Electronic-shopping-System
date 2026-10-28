<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderInventoryReservation;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use App\Services\OrderInventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class OrderInventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancelling_pending_order_releases_reservation_without_changing_physical_stock(): void
    {
        [$order, $inventory] = $this->makeReservedOrder(8, 3, 2);

        app(OrderInventoryService::class)->cancel($order->id);

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(8, (int) $inventory->fresh()->physical_qty);
        $this->assertSame(1, (int) $inventory->fresh()->reserved_qty);
    }

    public function test_fulfilling_pending_order_decreases_physical_and_reserved_stock_once(): void
    {
        [$order, $inventory] = $this->makeReservedOrder(8, 3, 2);

        app(OrderInventoryService::class)->fulfill($order->id);

        $this->assertSame('processing', $order->fresh()->status);
        $this->assertSame(6, (int) $inventory->fresh()->physical_qty);
        $this->assertSame(1, (int) $inventory->fresh()->reserved_qty);
        $this->assertDatabaseHas('inventory_movements', [
            'variant_id' => $inventory->variant_id,
            'quantity' => -2,
            'type' => 'outbound',
            'reference_id' => $order->id,
        ]);
    }

    public function test_completed_inventory_action_cannot_be_applied_twice(): void
    {
        [$order, $inventory] = $this->makeReservedOrder(8, 3, 2);
        $service = app(OrderInventoryService::class);
        $service->fulfill($order->id);

        try {
            $service->fulfill($order->id);
            $this->fail('A second fulfillment must be rejected.');
        } catch (ValidationException) {
            $this->assertSame(6, (int) $inventory->fresh()->physical_qty);
            $this->assertSame(1, (int) $inventory->fresh()->reserved_qty);
            $this->assertDatabaseCount('inventory_movements', 1);
        }
    }

    public function test_recorded_reservation_changes_only_its_allocated_warehouse(): void
    {
        [$order, $allocatedInventory] = $this->makeReservedOrder(8, 3, 2);
        $item = $order->items()->firstOrFail();
        $otherWarehouse = Warehouse::create([
            'name' => 'Other warehouse',
            'code' => 'WH-OTHER-'.strtoupper(uniqid()),
            'city' => 'Other city',
            'is_active' => true,
        ]);
        $otherInventory = WarehouseInventory::create([
            'warehouse_id' => $otherWarehouse->id,
            'variant_id' => $allocatedInventory->variant_id,
            'physical_qty' => 5,
            'reserved_qty' => 2,
            'low_stock_threshold' => 1,
        ]);
        OrderInventoryReservation::create([
            'order_item_id' => $item->id,
            'warehouse_inventory_id' => $allocatedInventory->id,
            'quantity' => 2,
        ]);

        app(OrderInventoryService::class)->cancel($order->id);

        $this->assertSame(1, (int) $allocatedInventory->fresh()->reserved_qty);
        $this->assertSame(2, (int) $otherInventory->fresh()->reserved_qty);
        $this->assertDatabaseHas('order_inventory_reservations', [
            'order_item_id' => $item->id,
            'status' => 'released',
        ]);
    }

    private function makeReservedOrder(int $physical, int $reserved, int $ordered): array
    {
        $product = Product::create([
            'name' => 'Test product',
            'slug' => 'test-product-'.uniqid(),
            'is_active' => true,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-'.uniqid(),
            'price' => 12.50,
            'attributes' => [],
        ]);
        $warehouse = Warehouse::create([
            'name' => 'Test warehouse',
            'code' => 'WH-'.uniqid(),
            'city' => 'Test city',
            'is_active' => true,
        ]);
        $inventory = WarehouseInventory::create([
            'warehouse_id' => $warehouse->id,
            'variant_id' => $variant->id,
            'physical_qty' => $physical,
            'reserved_qty' => $reserved,
            'low_stock_threshold' => 1,
        ]);
        $order = Order::create([
            'order_number' => 'ORD-'.strtoupper(uniqid()),
            'status' => 'pending',
            'total_amount' => 25,
            'discount_amount' => 0,
            'net_amount' => 25,
            'created_at' => now(),
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'variant_id' => $variant->id,
            'quantity' => $ordered,
            'price' => 12.50,
        ]);

        return [$order, $inventory];
    }
}
