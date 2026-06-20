<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
  use HasFactory,
  Notifiable;

  protected $fillable = ['name',
    'email',
    'password',
    'phone'];
  protected $hidden = ['password',
    'remember_token'];

  public function roles(): BelongsToMany {
    return $this->belongsToMany(Role::class, 'role_user');
  }
  public function warehouses() :BelongsToMany {
    return $this->belongsToMany(Warehouse::class, 'warehouse_user');
  }
  public function addresses(): HasMany {
    return $this->hasMany(UserAddress::class);
  }
  public function orders():HasMany {
    return $this->hasMany(Order::class);
  }
  public function wishlistProducts():BelongsToMany {
    return $this->belongsToMany(Product::class,'wishlists');
  }
}