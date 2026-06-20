<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponUsage extends Model
{
  public $timestamps = false;
  protected $fillable = ['coupon_id',
    'user_id',
    'order_id',
    'discount_obtained',
    'used_at'];

  public function coupon(): BelongsTo {
    return $this->belongsTo(Coupon::class);
  }

  public function user(): BelongsTo {
    return $this->belongsTo(User::class);
  }

  public function order(): BelongsTo {
    return $this->belongsTo(Order::class);
  }
}