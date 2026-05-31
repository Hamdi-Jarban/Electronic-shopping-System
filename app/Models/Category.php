<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    protected $primaryKey = 'category_id';
    protected $fillable = ['name', 'slug', 'parent_category_id'];

    // علاقة القسم الأب (القسم ينتمي لقسم أعلى منه)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_category_id', 'category_id');
    }

    // علاقة الأقسام الفرعية (القسم لديه أقسام فرعية كثيرة)
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_category_id', 'category_id');
    }

    // القسم يحتوي على العديد من المنتجات (متعدد لمتعدد)
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    }
}
