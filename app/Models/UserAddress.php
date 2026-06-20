<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
  public $timestamps = false;
  protected $fillable = ['user_id',
    'address_line1',
    'address_line2',
    'city',
    'country',
    'postal_code',
    'is_default'];

  public function user(): BelongsTo {
    return $this->belongsTo(User::class);
  }
}