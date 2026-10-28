<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderInventoryReservation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_item_id',
        'warehouse_inventory_id',
        'quantity',
        'status',
        'created_at',
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(WarehouseInventory::class, 'warehouse_inventory_id');
    }
}
