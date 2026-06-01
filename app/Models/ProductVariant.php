<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variant';
    protected $primaryKey = 'variant_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'SKU',
        'size_option',
        'color_option',
        'packaging',
        'price',
        'weight_kg',
        'image_url',
    ];
    public function product() {
        return $this->belongsTo(Product::class,'product_id','product_id');
    }
}
