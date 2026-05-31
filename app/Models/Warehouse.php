<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Warehouse extends Model
{
    protected $table = 'warehouses';
    protected $primaryKey = 'warehouse_id';
    protected $fillable = ['name', 'location'];

    // المستودع يحتوي على كميات مخزنية مختلفة
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class, 'warehouse_id', 'warehouse_id');
    }
}
