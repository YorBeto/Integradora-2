<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;


class LightSensor extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'LightSensor';
    protected $dates = ['event_date'];

    protected $casts = [
        'event_date' => 'datetime',
    ];
    protected $attributes = [
        'status' => 'off',
        'alert_triggered' => false,
        'alert_message' => '',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'status',
        'event_date',
        'alert_triggered',
        'alert_message',        
    ];
}
