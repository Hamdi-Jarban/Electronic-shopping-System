<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderHistory extends Model
{
  public $timestamps = false;
  protected $fillable = ['order_id',
    'changed_by',
    'old_status',
    'new_status',
    'comment',
    'created_at'];

  public function order(): BelongsTo {
    return $this->belongsTo(Order::class);
  }

  // الموظف أو النظام الذي غيّر الحالة
  public function modifier(): BelongsTo {
    return $this->belongsTo(User::class, 'changed_by');
  }
}