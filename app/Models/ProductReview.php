<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    public $timestamps = false;
    protected $fillable = ['product_id', 'user_id', 'rating', 'comment', 'created_at'];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
