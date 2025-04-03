<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class TemperatureHumiditySensor extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'TemperatureHumiditySensor';
    protected $dates = ['event_date'];
    protected $casts = [
        'event_date' => 'datetime',
    ];
    protected $attributes = [
        'alert_triggered' => false,
        'alert_message' => '',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $fillable =[
        'temperature_c',
        'humidity_percent',
        'event_date',
        'alert_triggered',
        'alert_message'
    ];
}
