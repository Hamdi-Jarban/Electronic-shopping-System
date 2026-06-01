<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'default_address',
        'preferences_json',
    ];

    protected $casts = [
        'preferences_json' => 'array',
    ];
}
