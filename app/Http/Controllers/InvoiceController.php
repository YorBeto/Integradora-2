<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\InvoiceGenerated;
use App\Models\Delivery;
use App\Models\Worker;
use Illuminate\Support\Facades\Validator;


class InvoiceController extends Controller
{
        public function generateInvoice()
    {
        $randomProductCount = rand(3, 10);
        $carrier = $this->selectCarriers(); 
        $items = $this->generateFakeData($randomProductCount); 

        $data = [
            'title' => 'Factura de compra',
            'date' => now(),
            'items' => $items,
            'total' => array_sum(array_column($items, 'grams')),
            'carrier' => $carrier 
        ];

        $pdf = Pdf::loadView('invoices.template', $data);
        $fileName = 'factura_' . time() . '.pdf';
        $filePath = 'invoices/' . $fileName;
        Storage::disk('s3')->put($filePath, $pdf->output());

        $fileUrl = Storage::disk('s3')->url($filePath);

        $invoice = Invoice::create([
            'details' => json_encode($data),
            'URL' => $fileUrl,
            'status' => 'Pending',
        ]);

        broadcast(new InvoiceGenerated($invoice));

        return response()->json([
            'message' => 'Factura generada correctamente',
            'invoice' => [
                'URL' => $invoice->URL,
                'status' => $invoice->status,
                'details' => json_decode($invoice->details)
            ]
        ]);
    }


        private function generateFakeData($count)
    {
        $products = DB::table('products')->select('id', 'name')->get()->toArray();

        if (empty($products)) {
            return response()->json(['error' => 'No hay productos registrados.'], 400);
        }

        shuffle($products);

        $data = [];
        foreach (range(1, $count) as $index) {
            $product = $products[$index % count($products)];

            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'grams' => rand(1, 5000) 
            ];
        }

        return $data;
    }

    public function index()
    {
        $user = auth()->user();
        $role = auth()->payload()->get('role');

        if ($role !== 'admin') {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción.'], 403);
        }

        $invoices = Invoice::where('status', 'Pending')
            ->get(['id','invoice_date' ,'URL', 'details', 'status'])
            ->map(function($invoice) {
                return [
                    'URL' => $invoice->URL,
                    $date = $invoice->invoice_date instanceof \Carbon\Carbon ? $invoice->invoice_date : \Carbon\Carbon::parse($invoice->invoice_date),
                    'status' => $invoice->status,
                    'details' => json_decode($invoice->details)
                ];
            });

        return response()->json($invoices);
    }

    private function selectCarriers()
    {
        $carriers = ['DHL', 'UPS', 'Estafeta', 'FedEx', 'Castores', 'En-trega', 'Redpack', 'Paquetexpress'];
        
        $randomCarrier = $carriers[array_rand($carriers)];

        $randomNumber = rand(1000, 9999);

        return "{$randomCarrier}-{$randomNumber}";
    }
    

            public function assignInvoice(Request $request, $invoiceId)
        {
            $validator = Validator::make($request->all(), [
                'worker_id' => 'required|integer|exists:workers,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            $workerId = $request->worker_id; 
            $worker = Worker::find($workerId); 

            if (!$worker) {
                return response()->json([
                    'error' => 'Trabajador no encontrado.'
                ], 404);
            }

            $invoice = Invoice::find($invoiceId);

            if (!$invoice) {
                return response()->json([
                    'error' => 'Factura no encontrada.'
                ], 404);
            }

            if ($invoice->status !== 'Pending') {
                return response()->json([
                    'error' => 'La factura ya fue asignada o completada'
                ], 400);
            }

            $invoice->update([
                'status' => 'Assigned',
                'assigned_to' => $worker->id
            ]);

            $delivery = Delivery::create([
                'invoice_id' => $invoiceId,
                'worker_id' => $worker->id,
                'delivery_date' => now(),
                'carrier' => json_decode($invoice->details)->carrier,
                'status' => 'Pending'
            ]);

            $invoiceDetails = json_decode($invoice->details);

            foreach ($invoiceDetails->items as $item) {
                DB::table('delivery_details')->insert([
                    'delivery_id' => $delivery->id, 
                    'product_id' => $item->id,
                    'quantity_weight' => $item->grams
                ]);
            }

            return response()->json([
                'message' => 'Factura asignada correctamente y detalles de entrega guardados.'
            ]);
        }
}
