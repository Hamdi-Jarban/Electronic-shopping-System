<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
  public $timestamps = false; // تم استخدام السجل التاريخي والـ created_at المخصص
  protected $fillable = ['user_id',
    'coupon_id',
    'order_number',
    'status',
    'total_amount',
    'discount_amount',
    'net_amount',
    'created_at'];

  public function user(): BelongsTo {
    return $this->belongsTo(User::class);
  }

  public function coupon(): BelongsTo {
    return $this->belongsTo(Coupon::class);
  }

  // عناصر الفاتورة للطلب
  public function items(): HasMany {
    return $this->hasMany(OrderItem::class);
  }

  // عمليات الدفع المرتبطة بالطلب
  public function payments(): HasMany {
    return $this->hasMany(Payment::class);
  }

  // سجل تتبع الحالات
  public function histories(): HasMany {
    return $this->hasMany(OrderHistory::class);
  }

  // عملية شحن الطلب
  public function shipment(): HasOne {
    return $this->hasOne(Shipment::class);
  }
}