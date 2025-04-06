<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PirSensor as Pir;
use App\Models\Area;
use App\Models\TemperatureHumiditySensor;
Use App\Models\LightSensor;



class SensorsController extends Controller
{
    public function lastTemperature()
    {
        $temperatures = Pir::orderBy('event_date', 'desc')->get();

        if ($temperatures->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json($temperatures);
    }

    public function sensoresporarea($area_id)
    {
        $area = Area::find($area_id);
    
        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }
    
        $temperatureSensors = TemperatureHumiditySensor::where('area_id', $area_id)->orderBy('event_date', 'desc')->first();
        $pirSensors = Pir::where('area_id', $area_id)->orderBy('event_date', 'desc')->first();
    
        $sensors = [
            'temperature_sensors' => $temperatureSensors,
            'pir_sensors' => $pirSensors,
        ];
    
        return response()->json($sensors);
    }
}
