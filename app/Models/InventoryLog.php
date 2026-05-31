<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryLog extends Model
{
    protected $primaryKey = 'log_id';
    const UPDATED_AT = null; // تعطيل updated_at
    protected $fillable = ['inventory_id', 'change_quantity', 'change_reason'];
}