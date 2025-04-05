<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemperatureHumiditySensor as ThSensor;
use App\Events\ThSensorUpdated;
use App\Models\TemperatureHumiditySensor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TemperatureHumiditySensorController extends Controller
{
    public function getLastTemperatureHumidityStatus()
    {
        $lastStatus = TemperatureHumiditySensor::orderBy('event_date', 'desc')->first();

        if (!$lastStatus) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json($lastStatus);
    }

        public function storeThData(Request $request)
    {
        $validated = $request->validate([
            'temperature_c' => 'required|numeric',
            'humidity_percent' => 'required|numeric',
            'device_id' => 'required|exists:devices,id'
        ]);
        
        $sensorData = ThSensor::create([
            'temperature_c' => $validated['temperature_c'],
            'humidity_percent' => $validated['humidity_percent'],
            'device_id' => $validated['device_id'],
            'event_date' => now()
        ]);
        
        // Emitir evento a Pusher
        event(new ThSensorUpdated($sensorData));
        
        return response()->json($sensorData, 201);
    }
}
