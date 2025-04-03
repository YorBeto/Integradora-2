<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LightSensor;

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
}
