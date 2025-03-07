<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'area_id',
        'description',
        'stock_weight',
        'exit_code',
        'image',
    ];

    protected $hidden = ['id', 'area_id', 'created_at', 'updated_at'];
}
