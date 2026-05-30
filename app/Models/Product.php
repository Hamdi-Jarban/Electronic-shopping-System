<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [];
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class)->wherePivot(
            'supplier_price',
            'lead_time_days',
            'minimum_order'
        );
    }
}
