<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
  use HasFactory;

  protected $fillable = ['parent_id',
    'name',
    'slug',
    'is_active'];

  public function parent(): BelongsTo {
    return $this->belongsTo(Category::class, 'parent_id');
  }

  // علاقة القسم بالأقسام الفرعية التابعة له (الشجرة)
  public function children(): HasMany {
    return $this->hasMany(Category::class, 'parent_id');
  }

  // علاقة القسم بالمنتجات (Many-to-Many)
  public function products(): BelongsToMany {
    return $this->belongsToMany(Product::class, 'category_product');
  }
}