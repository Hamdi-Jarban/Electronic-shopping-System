<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $primaryKey = 'variant_id';
    protected $fillable = ['product_id', 'SKU', 'size_option', 'color_option', 'packaging', 'price', 'weight_kg', 'image_url'];

    // المتغير ينتمي لمنتج أب أساسي
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // المتغير متواجد في مخزون المستودعات
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class, 'variant_id', 'variant_id');
    }
}
