<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
  use HasFactory;

  protected $fillable = ['brand_id',
    'name',
    'slug',
    'description',
    'summary',
    'is_active'];

  // ينتمي لبراند معين
  public function brand(): BelongsTo {
    return $this->belongsTo(Brand::class);
  }

  public function categories(): BelongsToMany {
    return $this->belongsToMany(Category::class, 'category_product');
  }

  public function variants(): HasMany {
    return $this->hasMany(ProductVariant::class);
  }

  public function images(): HasMany {
    return $this->hasMany(ProductImage::class);
  }
  public function defaultVariant() {
    return $this->hasOne(ProductVariant::class)->oldestOfMany();
  }
  public function featureImage() {
    return $this->hasOne(ProductImage::class)->where('is_featured',true);
  }

  // مراجعات وتقييمات العملاء
  public function reviews(): HasMany {
    return $this->hasMany(ProductReview::class);
  }
}