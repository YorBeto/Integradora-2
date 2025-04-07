<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Rfid extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'RfidSensor';

    // Campos que puedes asignar en masa (por ejemplo al hacer create())
    protected $fillable = [
        'rfid_code',
        'name',
        'position',
        'area',
        'event_date',
    ];

    // Si quieres que Laravel lo trate como fecha
    protected $dates = ['event_date'];

    // Opcional: si quieres cambiar el nombre de la clave primaria o su tipo
    protected $primaryKey = '_id';


}
