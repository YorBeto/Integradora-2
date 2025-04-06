<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemperatureHumiditySensor;
use App\Events\ThSensorUpdated;

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

    public function triggerThUpdate()
    {
        $lastStatus = TemperatureHumiditySensor::orderBy('event_date', 'desc')->first();
        
        if (!$lastStatus) {
            return response()->json(['message' => 'No data found'], 404);
        }
        
        event(new ThSensorUpdated($lastStatus));
        
        return response()->json(['message' => 'TH update triggered', 'data' => $lastStatus]);
    }
}