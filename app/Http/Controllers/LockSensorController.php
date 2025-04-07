<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LockSensor;
use App\Models\Rfid;

class LockSensorController extends Controller
{
    public function ultimosAccesos()
    {
        $eventos = LockSensor::where('origin', 'RFID')
            ->whereNotNull('rfid_code')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

            $accesos = $eventos->map(function ($evento) {
                $persona = Rfid::where('rfid_code', $evento->rfid_code)->first();
            
                $hora = isset($evento['$date'])
                    ? \Carbon\Carbon::parse($evento['$date'])->format('Y-m-d H:i:s')
                    : 'Sin fecha';
            
                return [
                    'nombre' => $persona->name ?? 'Desconocido',
                    'puesto' => $persona->position ?? 'No definido',
                    'area' => $persona->area ?? 'No definida',
                    'rfid_code' => $evento->rfid_code,
                    'hora' => $hora,
                ];
            });
        return response()->json($accesos);
    }
}
