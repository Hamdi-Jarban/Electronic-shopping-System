<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseInventory extends Model
{
    protected $table = 'warehouse_inventory';

    protected $fillable = [
        'warehouse_id',
        'variant_id',
        'physical_qty',
        'reserved_qty',
        'low_stock_threshold',
    ];

    public const CREATED_AT = null;
    public const UPDATED_AT = 'updated_at';

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
