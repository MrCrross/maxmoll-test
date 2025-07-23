<?php

namespace App\Models\Warehouses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';
    protected $fillable = [
        'name',
    ];
    public $timestamps = false;
}
