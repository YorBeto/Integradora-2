<?php

namespace App\Http\Controllers;

use App\Models\WeightSensor;
use App\Models\Product;
use Illuminate\Http\Request;

class WeightSensorController extends Controller
{
    public function lastRegister()
    {
        // Obtener el último registro de WeightSensor
        $ultimo = WeightSensor::orderBy('event_date', 'desc')->first();

        if ($ultimo) {
            // Buscar el producto usando el 'exit_code' que está en ambas tablas
            $producto = Product::where('exit_code', $ultimo->exit_code)->first();

            if ($producto) {
                if ($ultimo->status == 0) {
                    $producto->stock_weight -= $ultimo->weight_kg;
                    $action = 'restado'; // Acción realizada para depuración
                }

                if ($ultimo->status == 1) {
                    $producto->stock_weight += $ultimo->weight_kg;
                    $action = 'sumado'; // Acción realizada para depuración
                }

                $producto->save();

                return response()->json([
                    'success' => true,
                    'data' => $ultimo,
                    'action' => $action,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado.',
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró ningún registro.',
            ], 404);
        }
    }
}