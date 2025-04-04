<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temperature;
use App\Models\Pir;

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
}
