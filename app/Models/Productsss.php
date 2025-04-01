<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Productsss extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'image_url',
    ];

}
