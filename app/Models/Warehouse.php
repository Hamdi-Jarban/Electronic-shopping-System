<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
  use HasFactory;

  protected $fillable = ['name',
    'code',
    'address',
    'city',
    'is_active'];

  public function managers(): BelongsToMany {
    return $this->belongsToMany(User::class, 'warehouse_user');
  }

  // علاقة المستودع بحركات المخزون التاريخية
  public function movements(): HasMany {
    return $this->hasMany(InventoryMovement::class);
  }
}