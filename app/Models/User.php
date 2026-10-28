<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
  use HasFactory,
  Notifiable;

  protected $fillable = ['name',
    'email',
    'email_verified_at',
    'password',
    'phone'];
  protected $hidden = ['password',
    'remember_token'];
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

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
