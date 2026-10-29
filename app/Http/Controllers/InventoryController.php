<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\WarehouseInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = WarehouseInventory::query()
            ->with(['variant.product', 'warehouse'])
            ->orderBy('warehouse_id')
            ->orderBy('variant_id')
            ->paginate(30);

        return view('admin.inventory.index', compact('inventories'));
    }

    public function update(Request $request, WarehouseInventory $inventory)
    {
        $data = $request->validate([
            'physical_qty' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($data, $inventory, $request) {
            $lockedInventory = WarehouseInventory::query()->whereKey($inventory->id)->lockForUpdate()->firstOrFail();
            $newQuantity = (int) $data['physical_qty'];
            if ($newQuantity < (int) $lockedInventory->reserved_qty) {
                throw ValidationException::withMessages([
                    'physical_qty' => 'لا يمكن أن يكون الرصيد أقل من الكمية المحجوزة (' . $lockedInventory->reserved_qty . ').',
                ]);
            }

            $delta = $newQuantity - (int) $lockedInventory->physical_qty;
            if ($delta === 0) {
                return;
            }

            $lockedInventory->update(['physical_qty' => $newQuantity]);
            InventoryMovement::create([
                'warehouse_id' => $lockedInventory->warehouse_id,
                'variant_id' => $lockedInventory->variant_id,
                'user_id' => $request->user()->id,
                'quantity' => $delta,
                'type' => 'adjustment',
                'reference_type' => 'manual_adjustment',
                'reference_id' => $lockedInventory->id,
                'reason' => 'تعديل الرصيد من لوحة إدارة المخزون',
                'created_at' => now(),
            ]);
        });

        return back()->with('success', 'تم تحديث رصيد المخزون وتسجيل حركة التعديل.');
    }
}
