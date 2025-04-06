<?php

namespace App\Http\Controllers;

use App\Models\WeightSensor;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Events\TriggerLastRegisters;

class WeightSensorController extends Controller
{
    public function lastRegisters()
    {
        $ultimos = WeightSensor::orderBy('event_date', 'desc')->take(5)->get();
    
        if ($ultimos->isNotEmpty()) {
            $result = [];
    
            foreach ($ultimos as $index => $ultimo) {
                $producto = Product::where('exit_code', $ultimo->exit_code)->first();
    
                if ($producto) {
                    if ($index === 0 && !$ultimo->processed) {
                        if ($ultimo->status == 0) {
                            $producto->stock_weight -= $ultimo->weight_kg;
                            $action = 'producto entregado (stock actualizado)';
                        } elseif ($ultimo->status == 1) {
                            $producto->stock_weight += $ultimo->weight_kg;
                            $action = 'producto almacenado (stock actualizado)';
                        }
    
                        $producto->save();
    
                        // Marcar como procesado
                        $ultimo->processed = true;
                        $ultimo->save();
                    } else {
                        $action = $ultimo->processed ? 'ya procesado (sin afectar stock)' : 'sin afectar stock';
                    }
    
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
