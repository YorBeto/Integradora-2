<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
            'total' => array_sum(array_column($items, 'grams')) // Sumar los gramos
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
        // Obtener todos los nombres de productos registrados en la base de datos
        $productNames = DB::table('products')->pluck('name')->toArray();
        
        if (empty($productNames)) {
            return response()->json(['error' => 'No hay productos registrados.'], 400);
        }
        
        // Mezclar los nombres para obtener una selección aleatoria
        shuffle($productNames);
    
        $data = [];
        foreach (range(1, $count) as $index) {
            // Usar los nombres disponibles, repitiéndolos si se necesitan más de los que existen
            $name = $productNames[$index % count($productNames)];
            $data[] = [
                'name' => $name,
                'grams' => rand(1, 1000) // Generar un peso aleatorio entre 1 y 1000 gramos
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
