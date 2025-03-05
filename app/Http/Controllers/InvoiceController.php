<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class InvoiceController extends Controller
{
    public function generateInvoice()
    {
        // Generar un número aleatorio de productos entre 3 y 10
        $randomProductCount = rand(3, 10);

        // Generar datos falsos
        $items = $this->generateFakeData($randomProductCount);

        // Datos de ejemplo
        $data = [
            'title' => 'Factura de compra',
            'date' => now(),
            'items' => $items,
            'total' => array_sum(array_column($items, 'KG')) // Sumar los kilogramos
        ];

        // Generar PDF
        $pdf = Pdf::loadView('invoices.template', $data);

        // Nombre del archivo
        $fileName = 'factura_' . time() . '.pdf';

        // Guardar en S3
        $filePath = 'invoices/' . $fileName;
        Storage::disk('s3')->put($filePath, $pdf->output());

        $fileUrl = Storage::disk('s3')->url($filePath);

        // Guardar en la BD
        $invoice = Invoice::create([
            'details' => json_encode($data),
            'URL' => $fileUrl,
            'status' => 'Pending'
        ]);

        return response()->json([
            'message' => 'Factura generada correctamente',
            'invoice' => $invoice
        ]);
    }

    public function getInvoices(){

        $invoices = Invoice::select('URL')
        ->where('status', 'Pending')
        ->get()
        ->map(function($invoice){
            return [
                'URL' => $invoice->URL
            ];
        });

        return response()->json($invoices);

    }
}
