<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['code', 'type', 'value', 'start_date', 'end_date', 'usage_limit', 'used_count'];

    // عمليات استخدام هذا الكوبون عبر العملاء
    public function usages(): HasMany {
        return $this->hasMany(CouponUsage::class);
    }
}
