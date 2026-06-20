<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderReturnItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['order_return_id', 'variant_id', 'quantity', 'condition'];

    public function returnRequest(): BelongsTo {
        return $this->belongsTo(OrderReturn::class, 'order_return_id');
    }

    public function variant(): BelongsTo {
        return $this->belongsTo(ProductVariant::class);
    }
}
