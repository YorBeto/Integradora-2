<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PirSensor;
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
        $lastStatus->area_name = $areaName ?? 'Área desconocida';

        return response()->json($lastStatus);
    }

    public function triggerPirUpdate()
    {
        $lastStatus = PirSensor::orderBy('event_date', 'desc')->first();
        
        if (!$lastStatus) {
            return response()->json(['message' => 'No data found'], 404);
        }

        $areaName = Area::where('id', $lastStatus->area_id)->value('name');
        $lastStatus->area_name = $areaName ?? 'Área desconocida';
        
        event(new PirSensorUpdated($lastStatus));
        
        return response()->json(['message' => 'PIR update triggered', 'data' => $lastStatus]);
    }

    public function getAllPirStatus()
    {
        $allStatus = PirSensor::orderBy('event_date', 'desc')->get();

        if ($allStatus->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json($allStatus);
    }
}