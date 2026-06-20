<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
  public $timestamps = false;
  protected $fillable = ['name',
    'display_name',
    'group_name',
    'created_at'];

  // الأدوار التي تمتلك هذه الصلاحية
  public function roles(): BelongsToMany {
    return $this->belongsToMany(Role::class, 'permission_role');
  }
}