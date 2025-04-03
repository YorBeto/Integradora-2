<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PirSensor;

class PirSensorController extends Controller
{
    public function getLastPirStatus()
    {
        $lastStatus = PirSensor::orderBy('event_date', 'desc')->first();

        if (!$lastStatus) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json($lastStatus);
    }
}
