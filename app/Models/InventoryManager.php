<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryManager extends Model
{
    protected $table = 'inventory_manager';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'warehouse_id',
        'salary',
    ];
}
