<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    public function index()
    {
        // Consulta la vista que contiene los deliveries
        $deliveries = DB::table('delivery_view')->get();

        return response()->json(['data' => $deliveries], 200, [], JSON_PRETTY_PRINT);
    }

        public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|integer|exists:invoices,id',
            'worker_id' => 'required|integer|exists:workers,id',
        ]);



        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $existingDelivery = DB::table('deliveries')
            ->where('invoice_id', $request->invoice_id)
            ->whereIn('status', ['Completed', 'Pending'])
            ->exists(); 

        if ($existingDelivery) {
            return response()->json(['error' => 'Esta factura ya ha sido entregada completamente.'], 400);
        }

        $invoice = DB::table('invoices')
        ->where('id', $request->invoice_id)
        ->first();

        $details = json_decode($invoice->details, true);
        $carrier = $details['carrier'] ?? null; 


        try {
            DB::statement("CALL CreateDeliveryFromInvoice(?, ?, ?)", [
                $request->invoice_id,
                $request->worker_id,
                $carrier
            ]);

            return response()->json([
                'message' => 'Entrega creada con éxito'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al crear la entrega',
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
