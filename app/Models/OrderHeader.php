<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHeader extends Model
{
    protected $table = 'order_header';
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'order_date',
        'total_amount',
        'order_status',
        'shipping_address',
        'notes',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
