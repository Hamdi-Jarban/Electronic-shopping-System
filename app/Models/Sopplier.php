<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //
    public function products()
    {
        return $this->belongsToMany(Product::class)->wherePivot(
            'supplier_price',
            'lead_time_days',
            'minimum_order'
        );
    }
}
