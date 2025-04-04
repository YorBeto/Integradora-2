<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Obtiene todos los productos.
     */
    public function index()
    {
        $products = DB::table('products')
            ->join('areas', 'products.area_id', '=', 'areas.id')
            ->select(
                'products.name',
                'products.description',
                'products.stock_weight',
                'products.exit_code',
                'products.image',
                'areas.name as area'
            )
            ->get();
        return response()->json($products);
    }

    public function stock(Request $request)
    {
        // Validación
        $request->validate([
            'exit_code' => 'required|string',
            'stock_weight' => 'required|numeric',
        ]);

        // Buscar el producto por exit_code
        $producto = DB::table('products')
            ->where('exit_code', $request->exit_code)
            ->first();

        if (!$producto) {
            return response()->json(['message' => 'No se encontró un producto con ese exit_code.'], 404);
        }

        $nuevoPeso = $producto->stock_weight + $request->stock_weight;

        // Actualizar en la base de datos
        DB::table('products')
            ->where('exit_code', $request->exit_code)
            ->update(['stock_weight' => $nuevoPeso]);

        return response()->json([
            'message' => 'Stock actualizado correctamente.',
            'exit_code' => $request->exit_code,
            'nuevo_stock_weight' => $nuevoPeso
        ], 200);
    }
}
