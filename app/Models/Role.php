<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'display_name', 'description'];

    // الصلاحيات المرتبطة بهذا الدور
    public function permissions(): BelongsToMany {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    // المستخدمين المسند إليهم هذا الدور
    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
