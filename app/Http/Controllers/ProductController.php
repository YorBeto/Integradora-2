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
}
