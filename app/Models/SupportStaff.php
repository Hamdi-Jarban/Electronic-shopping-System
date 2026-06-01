<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportStaff extends Model
{
    protected $table = 'support_staff';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'department',
        'is_online',
        'salary',
    ];

    protected $casts = [
        'is_online' => 'boolean',
    ];
}
