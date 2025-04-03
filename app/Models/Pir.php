<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Pir extends Model
{
    protected $connection = 'mongodb'; // Conexión a MongoDB
    protected $collection = 'PirSensor'; // Nombre de la colección en MongoB

    protected $fillable = [
        '_id',
        'motion_detected',
        'event_date',
        'alert_triggered',
        'alert_message',
    ];
}
