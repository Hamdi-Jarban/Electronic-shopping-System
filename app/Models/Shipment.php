<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = ['order_id', 'warehouse_id', 'carrier_name', 'tracking_number', 'status', 'shipping_cost', 'shipping_price', 'shipped_at', 'delivered_at'];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    public function warehouse(): BelongsTo {
        return $this->belongsTo(Warehouse::class);
    }
}
