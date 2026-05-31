<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    protected $fillable = ['company_name', 'contact_person', 'email', 'phone'];
}
