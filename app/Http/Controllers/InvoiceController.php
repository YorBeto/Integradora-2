<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class InvoiceController extends Controller
{
        public function generateInvoice()
    {
        // Datos de ejemplo
        $data = [
            'title' => 'Factura de compra',
            'date' => now(),
            'items' => [
                ['name' => 'Producto 1', 'price' => 50],
                ['name' => 'Producto 2', 'price' => 30],
            ],
            'total' => 80
        ];

        // Generar PDF
        $pdf = Pdf::loadView('invoices.template', $data);
        
        // Nombre del archivo
        $fileName = 'factura_' . time() . '.pdf';

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
}
