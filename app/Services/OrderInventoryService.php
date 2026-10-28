<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderInventoryReservation;
use App\Models\WarehouseInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderInventoryService
{
    public function cancel(int $orderId): Order
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::whereKey($orderId)->lockForUpdate()->firstOrFail();
            $this->ensurePending($order);
            $this->changeReservedInventory($order, false);
            $order->update(['status' => 'cancelled']);

            return $order->refresh();
        }, 3);
    }

    /** Confirm and dispatch a pending order; reserved stock becomes sold stock. */
    public function fulfill(int $orderId, ?int $userId = null): Order
    {
        return DB::transaction(function () use ($orderId, $userId) {
            $order = Order::whereKey($orderId)->lockForUpdate()->firstOrFail();
            $this->ensurePending($order);
            $this->changeReservedInventory($order, true, $userId);
            $order->update(['status' => 'processing']);

            return $order->refresh();
        }, 3);
    }

    private function ensurePending(Order $order): void
    {
        if ($order->status !== 'pending') {
            throw ValidationException::withMessages([
                'order' => 'لا يمكن تنفيذ هذا الإجراء؛ يجب أن تكون حالة الطلب معلّقة.',
            ]);
        }
    }

    /**
     * Inventory reservations are aggregated by variant in the current schema.
     * Consume/release them deterministically across warehouses in one transaction.
     */
    private function changeReservedInventory(Order $order, bool $fulfill, ?int $userId = null): void
    {
        $items = $order->items()->orderBy('variant_id')->get();

        if ($items->isEmpty()) {
            throw ValidationException::withMessages(['order' => 'لا يحتوي الطلب على منتجات.']);
        }

        foreach ($items as $item) {
            $allocations = OrderInventoryReservation::where('order_item_id', $item->id)
                ->where('status', 'reserved')
                ->orderBy('warehouse_inventory_id')
                ->lockForUpdate()
                ->get();

            $hasRecordedAllocation = OrderInventoryReservation::where('order_item_id', $item->id)->exists();
            if ($hasRecordedAllocation && (int) $allocations->sum('quantity') !== (int) $item->quantity) {
                throw ValidationException::withMessages([
                    'order' => 'تخصيص الحجز لهذا البند غير مكتمل أو سبق تحريره؛ لم يتم تغيير المخزون.',
                ]);
            }

            if ($hasRecordedAllocation) {
                foreach ($allocations as $allocation) {
                    $inventory = WarehouseInventory::whereKey($allocation->warehouse_inventory_id)
                        ->lockForUpdate()
                        ->first();
                    if (!$inventory || $inventory->variant_id !== $item->variant_id) {
                        throw ValidationException::withMessages(['order' => 'المخزون المرتبط ببند الطلب غير صالح.']);
                    }
                    $this->applyInventoryChange($inventory, $item->variant_id, (int) $allocation->quantity, $order, $fulfill, $userId);
                    $allocation->update(['status' => $fulfill ? 'fulfilled' : 'released']);
                }
                continue;
            }

            // Compatibility for pending orders created before the reservation ledger migration.
            $rows = WarehouseInventory::where('variant_id', $item->variant_id)
                ->where('reserved_qty', '>', 0)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();
            if ((int) $rows->sum('reserved_qty') < $item->quantity) {
                throw ValidationException::withMessages(['order' => 'الحجز المخزني غير كافٍ؛ لم يتم تغيير المخزون.']);
            }
            $remaining = (int) $item->quantity;
            foreach ($rows as $inventory) {
                $quantity = min((int) $inventory->reserved_qty, $remaining);
                if ($quantity > 0) {
                    $this->applyInventoryChange($inventory, $item->variant_id, $quantity, $order, $fulfill, $userId);
                    $remaining -= $quantity;
                }
                if ($remaining === 0) {
                    break;
                }
            }
        }
    }

    private function applyInventoryChange(WarehouseInventory $inventory, int $variantId, int $quantity, Order $order, bool $fulfill, ?int $userId): void
    {
        if ($quantity < 1 || $inventory->reserved_qty < $quantity) {
            throw ValidationException::withMessages(['order' => 'الحجز المخزني غير كافٍ؛ لم يتم تغيير المخزون.']);
        }
        if ($fulfill && $inventory->physical_qty < $quantity) {
            throw ValidationException::withMessages(['order' => 'المخزون الفعلي أقل من الكمية المحجوزة؛ لم يتم تغيير المخزون.']);
        }

        $inventory->reserved_qty -= $quantity;
        if ($fulfill) {
            $inventory->physical_qty -= $quantity;
        }
        $inventory->save();

        if ($fulfill) {
            InventoryMovement::create([
                'warehouse_id' => $inventory->warehouse_id,
                'variant_id' => $variantId,
                'user_id' => $userId,
                'quantity' => -$quantity,
                'type' => 'outbound',
                'reference_type' => Order::class,
                'reference_id' => $order->id,
                'reason' => 'تنفيذ الطلب '.$order->order_number,
                'created_at' => now(),
            ]);
        }
    }
}
