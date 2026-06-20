<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderReturn extends Model
{
  protected $fillable = ['order_id',
    'user_id',
    'return_number',
    'status',
    'refund_amount',
    'reason'];

  public function order(): BelongsTo {
    return $this->belongsTo(Order::class);
  }

  public function user(): BelongsTo {
    return $this->belongsTo(User::class);
  }

  // العناصر الفرعية في المرتجع الحالي
  public function items(): HasMany {
    return $this->hasMany(OrderReturnItem::class);
  }
}