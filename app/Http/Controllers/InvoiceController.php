<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\InvoiceGenerated;


class InvoiceController extends Controller
{
    public function generateInvoice()
    {
        $randomProductCount = rand(1, 6);
        $items = $this->generateFakeData($randomProductCount);

        $data = [
            'title' => 'Factura de compra',
            'date' => now(),
            'items' => $items,
            'total' => array_sum(array_column($items, 'grams'))
        ];

        $pdf = Pdf::loadView('invoices.template', $data);
        $fileName = 'factura_' . time() . '.pdf';
        $filePath = 'invoices/' . $fileName;
        Storage::disk('s3')->put($filePath, $pdf->output());

        $fileUrl = Storage::disk('s3')->url($filePath);

        $invoice = Invoice::create([
            'details' => json_encode($data),
            'URL' => $fileUrl,
            'status' => 'Pending'
        ]);

        // Verificar si el WebSocket se dispara correctamente
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
}
