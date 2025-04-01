<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Invoice;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;


class DeliveryController extends Controller
{
    public function index()
    {
        // Consulta la vista que contiene los deliveries
        $deliveries = DB::table('delivery_view')->get();

        return response()->json(['data' => $deliveries], 200, [], JSON_PRETTY_PRINT);
    }


    public function completeDelivery($deliveryId)
    {
        $delivery = Delivery::findOrFail($deliveryId);
        
        if ($delivery->worker_id !== auth()->id()) {
            return response()->json(['error' => 'No tienes permiso para completar esta entrega.'], 403);
        }

        if ($delivery->status !== 'Pending') {
            return response()->json(['error' => 'La entrega ya fue procesada o completada.'], 400);
        }

        $delivery->update([
            'status' => 'Completed',
            'delivery_date' => now()
        ]);

        $invoice = Invoice::findOrFail($delivery->invoice_id);
        $invoice->update(['status' => 'Completed']);

        return response()->json(['message' => 'Entrega completada correctamente.']);
    }



}
