<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Brand extends Model
{
    protected $primaryKey = 'brand_id';
    protected $fillable = ['name', 'slug', 'logo_url'];

    // العلامة التجارية لها العديد من المنتجات
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id', 'brand_id');
    }
}
