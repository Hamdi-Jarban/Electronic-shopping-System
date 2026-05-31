<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $primaryKey = 'image_id';
    protected $fillable = ['product_id', 'image_url', 'sort_order'];
}
