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
        $randomProductCount = rand(3, 10);
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
        $user = auth()->user();
        $role = auth()->payload()->get('role');

        if ($role !== 'admin') {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción.'], 403);
        }

        $invoices = Invoice::where('status', 'Pending')
            ->get(['id', 'URL', 'details', 'status'])
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
