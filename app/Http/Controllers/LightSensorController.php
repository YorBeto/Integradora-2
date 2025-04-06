<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LightSensor;
use App\Events\LightSensorUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LightSensorController extends Controller
{
    public function getLastLightStatus()
    {
        $lastStatus = LightSensor::orderBy('event_date', 'desc')->first();

        if (!$lastStatus) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json($lastStatus);
    }

    // En tu controlador Laravel
public function triggerLightUpdate()
{
    $lastStatus = LightSensor::orderBy('event_date', 'desc')->first();
    
    if (!$lastStatus) {
        return response()->json(['message' => 'No data found'], 404);
    }
    
    // Asegúrate que los campos coincidan con lo que espera Angular
    $dataToSend = [
        'status' => $lastStatus->status, // 'on' u 'off'
        'event_date' => $lastStatus->event_date,
        // otros campos que uses en el frontend
    ];
    
    event(new LightSensorUpdated($dataToSend));
    
    return response()->json(['message' => 'Update triggered']);
}

    
}
