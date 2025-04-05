<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class WeightSensor extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'WeightSensor';

    protected $dates = ['event_date'];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        '_id',
        'exit_code',
        'weight_kg',
        'status',
        'event_date',
    ];
}
