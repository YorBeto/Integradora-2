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

    public function storeLightData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
            'area_id' => 'required|exists:areas,id',
            'alert_triggered' => 'required|boolean',
            'alert_message' => 'nullable|string',
            'event_date' => 'required|date'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validated = $validator->validated();
        Log::info('Light sensor data received:', $validated);
        
        $sensorData = LightSensor::create([
            'status' => $validated['status'],
            'area_id' => $validated['area_id'],
            'alert_triggered' => $validated['alert_triggered'],
            'alert_message' => $validated['alert_message'],
            'event_date' => $validated['event_date'],
        ]);
        
        // Emitir evento a Pusher
        event(new LightSensorUpdated($sensorData));
        
        return response()->json($sensorData, 201);
    }
}
