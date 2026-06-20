<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
  use HasFactory;

  protected $fillable = ['product_id',
    'sku',
    'price',
    'compare_at_price',
    'attributes'];

  // نقوم بعمل كاست للحقل ليعود كمصفوفة مصفوفات آلياً عند الاستدعاء
  protected $casts = [
    'attributes' => 'array',
  ];

  public function product(): BelongsTo {
    return $this->belongsTo(Product::class);
  }

  public function images(): HasMany {
    return $this->hasMany(ProductImage::class, 'variant_id','id');
  }
  public function orderItems(): HasMany {
    return $this->hasMany(OrderItem::class, 'variant_id','id');
  }
  public function inventories(): HasMany {
    return $this->hasMany(InventoryMovement::class, 'variant_id','id');
  }
}