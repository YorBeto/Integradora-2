<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeightSensor;
use App\Models\Product;
use App\Events\WeightSensorUpdated;

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

                        $ultimo->processed = true;
                        $ultimo->save();
                    } else {
                        $action = $ultimo->processed ? 'producto entregado (stock actualizado)' : 'producto almacenado (stock actualizado)';
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

    public function triggerLastRegisters()
    {
        $ultimo = WeightSensor::orderBy('event_date', 'desc')->first();

        if (!$ultimo) {
            return response()->json(['message' => 'No data found'], 404);
        }

        $producto = Product::where('exit_code', $ultimo->exit_code)->first();

        $dataToSend = [
            'exit_code' => $ultimo->exit_code,
            'weight_kg' => $ultimo->weight_kg,
            'status' => $ultimo->status,
            'area_id' => $ultimo->area_id,
            'event_date' => $ultimo->event_date,
            'processed' => $ultimo->processed,
            'action' => $ultimo->processed ? 'procesado' : 'procesado',
        ];

        event(new WeightSensorUpdated($dataToSend));

        return response()->json(['message' => 'Weight sensor update triggered']);
    }
}
