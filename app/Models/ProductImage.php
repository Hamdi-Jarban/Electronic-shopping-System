<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
  public $timestamps = false;
  protected $fillable = ['product_id',
    'variant_id',
    'image_path',
    'is_featured',
    'sort_order'];

  public function product(): BelongsTo {
    return $this->belongsTo(Product::class);
  }

  public function variant(): BelongsTo {
    return $this->belongsTo(ProductVariant::class);
  }
}