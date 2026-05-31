<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryManager extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false; // لأن المفتاح الأساسي قادم من جدول المستخدمين
    protected $fillable = ['user_id', 'warehouse_id', 'salary'];
}
