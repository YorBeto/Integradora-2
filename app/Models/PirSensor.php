<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model;
class PirSensor extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'PirSensor';


    protected $fillable =[
        'motion_detected',
        'alert_triggered',
        'alert_message',
        'event_date',
    ];

    protected $dates = [
        'event_date',
    ];
    protected $casts = [
        'event_date' => 'datetime',
    ];
    protected $attributes = [
        'motion_detected' => false,
        'alert_triggered' => false,
        'alert_message' => '',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
