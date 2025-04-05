<?php

namespace App\Http\Controllers;

use App\Models\WeightSensor;
use App\Models\Product;
use Illuminate\Http\Request;

class WeightSensorController extends Controller
{
    public function lastRegisters()
    {
        // Obtener los últimos 5 registros de WeightSensor
        $ultimos = WeightSensor::orderBy('event_date', 'desc')->take(5)->get();

        if ($ultimos->isNotEmpty()) {
            $result = [];

            foreach ($ultimos as $ultimo) {
                // Buscar el producto usando el 'exit_code' que está en ambas tablas
                $producto = Product::where('exit_code', $ultimo->exit_code)->first();

                if ($producto) {
                    if ($ultimo->status == 0) {
                        $producto->stock_weight -= $ultimo->weight_kg;
                        $action = 'producto entregado'; // Acción realizada para depuración
                    }

                    if ($ultimo->status == 1) {
                        $producto->stock_weight += $ultimo->weight_kg;
                        $action = 'producto almacenado'; // Acción realizada para depuración
                    }

                    $producto->save();

                    // Agregar el valor de 'action' al objeto $ultimo
                    $ultimo->action = $action;
                } else {
                    $ultimo->action = 'Producto no encontrado';
                }

                $result[] = $ultimo;
            }

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron registros.',
            ], 404);
        }
    }
}