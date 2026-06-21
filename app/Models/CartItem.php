<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //
    use HasFactory;
    protected $table = 'cart_items';
    protected $fillable = [
        'user_id',
        'session_token',
        'variant_id',
        'quantity'
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class,'variant_id');
    }
}
