<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LockSensor;
use App\Models\Rfid;
use App\Events\LockSensorUpdated;

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

            $fecha = $evento->date->format('Y-m-d H:i:s');
        
            return [
                'nombre' => $persona->name ?? 'Desconocido',
                'puesto' => $persona->position ?? 'No definido',
                'area' => $persona->area ?? 'No definida',
                'rfid_code' => $evento->rfid_code,
                'fecha' => $fecha,
                
            ];
        });

        return response()->json($accesos);
    }

    public function getLastLockStatus()
    {
        $lastStatus = LockSensor::orderBy('date', 'desc')->first();

        if (!$lastStatus) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json($lastStatus);
    }

    public function triggerLockUpdate()
    {
        $lastStatus = LockSensor::orderBy('date', 'desc')->first();
        
        if (!$lastStatus) {
            return response()->json(['message' => 'No data found'], 404);
        }
        
        event(new LockSensorUpdated($lastStatus));
        
        return response()->json(['message' => 'Lock update triggered', 'data' => $lastStatus]);
    }
}