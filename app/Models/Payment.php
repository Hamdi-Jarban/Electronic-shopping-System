<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public $timestamps = false;
    protected $fillable = ['order_id', 'gateway', 'transaction_id', 'status', 'amount', 'created_at'];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }
}

