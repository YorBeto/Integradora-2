<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Temperature extends Model
{
    protected $connection = 'mongodb'; // Conexión a MongoDB
    protected $collection = 'TemperatureHumiditySensor'; // Nombre de la colección en MongoB

    protected $fillable = [
        '_id',
        'temperature_c',
        'humidity_percent',
        'event_date',
        'alert_triggered',
        'alert_message',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];
}
