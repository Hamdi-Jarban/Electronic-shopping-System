<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'inventory_id';
    public $timestamps = false;

    protected $fillable = [
        'variant_id',
        'warehouse_id',
        'quantity_in_stock',
        'reorder_level',
        'reorder_quantity',
        'last_updated',
    ];
}
