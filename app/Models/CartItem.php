<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_item';
    protected $primaryKey = 'cart_item_id';
    public $timestamps = false;

    protected $fillable = ['cart_id', 'variant_id', 'quantity'];
}
