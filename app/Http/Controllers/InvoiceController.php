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
            'invoice' => $fileUrl
        ]);
    }


    private function generateFakeData($count)
    {
        $faker = Faker::create();
        $data = [];

        foreach (range(1, $count) as $index) {
            $data[] = [
                'name' => $faker->word(),
                'KG' => $faker->randomFloat(2, 1, 100)
            ];
        }
        
        return $data;
    }

    public function getInvoices()
{
    $invoices = Invoice::select('URL', 'details', 'status') 
        ->where('status', 'Pending')
        ->get()
        ->map(function($invoice) {
            return [
                'URL' => $invoice->URL,
                'status' => $invoice->status,
                'details' => json_decode($invoice->details)
            ];
        });

    return response()->json($invoices);
}

}
