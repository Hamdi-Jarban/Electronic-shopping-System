<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class CartItem extends Model
{
    protected $table = 'cart_item';
    protected $primaryKey = 'cart_item_id';
    public $timestamps = false;

    protected $fillable = ['cart_id', 'variant_id', 'quantity'];
    public function cart()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class,'variant_id','variant_id');
    }
}
