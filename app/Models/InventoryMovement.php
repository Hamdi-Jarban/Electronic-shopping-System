<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
  public $timestamps = false;
  protected $fillable = ['warehouse_id',
    'variant_id',
    'user_id',
    'quantity',
    'type',
    'reference_type',
    'reference_id',
    'reason',
    'created_at'];

  public function warehouse(): BelongsTo {
    return $this->belongsTo(Warehouse::class);
  }

  public function variant(): BelongsTo {
    return $this->belongsTo(ProductVariant::class);
  }

  public function user(): BelongsTo {
    return $this->belongsTo(User::class);
  }
}