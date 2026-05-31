<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    protected $primaryKey = 'inventory_id';
    protected $fillable = ['variant_id', 'warehouse_id', 'quantity_in_stock', 'reorder_level', 'reorder_quantity'];

    // المخزن يرتبط بمتغير معين لمنتج
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'variant_id');
    }

    // المخزن ينتمي لمستودع محدد
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    // المخزن لديه سجل حركات حركة كامل
    public function logs(): HasMany
    {
        return $this->hasMany(InventoryLog::class, 'inventory_id', 'inventory_id');
    }
}
