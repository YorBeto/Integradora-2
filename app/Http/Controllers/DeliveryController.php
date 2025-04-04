<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Invoice;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeliveryController extends Controller
{
    public function index()
    {
        // Consulta la vista que contiene los deliveries
        $deliveries = DB::table('delivery_view')->get();

        return response()->json(['data' => $deliveries], 200, [], JSON_PRETTY_PRINT);
    }

    public function show()
    {
        try {
            // Obtener el usuario desde el token
            $payload = JWTAuth::parseToken()->getPayload();
            $user = $payload->get('user');

            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            // Buscar en la tabla 'people' la persona asociada a este usuario
            $person = DB::table('people')->where('user_id', $user['id'])->first();

            if (!$person) {
                return response()->json(['error' => 'No se encontró información en people'], 404);
            }

            // Buscar el trabajador en 'workers' asociado a esta persona
            $worker = DB::table('workers')->where('person_id', $person->id)->first();

            if (!$worker) {
                return response()->json(['error' => 'No se encontró un trabajador asociado'], 404);
            }

            // Obtener las entregas donde worker_id coincida
            $deliveries = DB::table('delivery_view')
                ->where('worker_id', $worker->id)
                ->get();

            return response()->json(['data' => $deliveries], 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno del servidor', 'message' => $e->getMessage()], 500);
        }
    }

    public function completeDelivery($id, Request $request)
    {
        $delivery = Delivery::findOrFail($id);
    
        if ($delivery->status !== 'Pending') {
            return response()->json(['error' => 'La entrega ya fue procesada o completada.'], 400);
        }
    
        $validator = Validator::make($request->all(), [
            'carrier' => 'required|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'Datos inválidos.', 'details' => $validator->errors()], 422);
        }
    
        if ($request->carrier !== $delivery->carrier) {
            return response()->json(['error' => 'El carrier proporcionado no coincide con el de la entrega.'], 400);
        }
    
        $delivery->update([
            'status' => 'Completed',
            'delivery_date' => now()
        ]);
    
        Invoice::where('id', $delivery->invoice_id)->update(['status' => 'Completed']);
    
        return response()->json(['message' => 'Entrega completada correctamente.']);
    }
    
}
