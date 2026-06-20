<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
  public $timestamps = false;
  protected $fillable = ['order_id',
    'variant_id',
    'quantity',
    'price'];

  public function order(): BelongsTo {
    return $this->belongsTo(Order::class);
  }

  public function variant(): BelongsTo {
    return $this->belongsTo(ProductVariant::class);
  }
}