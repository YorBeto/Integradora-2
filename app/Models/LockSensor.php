<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class LockSensor extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'LockSensor'; 

    protected $fillable = [
        'area_id',
        'event_type',
        'date',
        'origin',
    ];

    protected $dates = ['date'];

    protected $primaryKey = '_id';

    
    protected $casts = [
        'date' => 'datetime',
    ];

}
