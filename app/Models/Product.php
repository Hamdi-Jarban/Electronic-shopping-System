<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $fillable = ['name', 'slug', 'description', 'base_image_url', 'is_active', 'brand_id'];

    // المنتج ينتمي لعلامة تجارية واحدة
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    // المنتج يحتوي على ألبوم صور متعدد
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    // المنتج لديه العديد من المتغيرات (مقاسات/ألوان)
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id');
    }

    // المنتج ينتمي لعدة أقسام
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    // المنتج يمكن توريده من عدة موردين
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier', 'product_id', 'supplier_id')
            ->withPivot('supply_price', 'lead_time_days', 'minimum_order');
    }
}
