<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PirSensor;
use App\Models\Device;
use App\Models\Area;
use App\Events\PirSensorUpdated;

class PirController extends Controller
{
    public function getLastPirStatus()
    {
        $lastStatus = PirSensor::orderBy('event_date', 'desc')->first();

        if (!$lastStatus) {
            return response()->json(['message' => 'No data found'], 404);
        }

        $areaName = Area::where('id', $lastStatus->area_id)->value('name');

        if (!$areaName) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        $lastStatus->area_name = $areaName;

        return response()->json($lastStatus);
    }

    public function getAllPirStatus()
    {
        $allStatus = PirSensor::orderBy('event_date', 'desc')->get();

        if ($allStatus->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json($allStatus);
    }
    
    // Nuevo método para recibir y transmitir datos
    public function storeSensorData(Request $request)
    {
        $validated = $request->validate([
            'motion_detected' => 'required|boolean',
            'area_id' => 'required|exists:areas,id',
            'device_id' => 'required|exists:devices,id'
        ]);
        
        $sensorData = PirSensor::create([
            'motion_detected' => $validated['motion_detected'],
            'area_id' => $validated['area_id'],
            'device_id' => $validated['device_id'],
            'event_date' => now()
        ]);
        
        // Obtener nombre del área
        $areaName = Area::find($validated['area_id'])->name;
        $sensorData->area_name = $areaName;
        
        // Emitir evento a Pusher
        event(new PirSensorUpdated($sensorData));
        
        return response()->json($sensorData, 201);
    }
}