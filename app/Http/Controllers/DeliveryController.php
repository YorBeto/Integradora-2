<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Person;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function index()
    {
        // Consulta directamente la vista
        $deliveries = DB::table('delivery_view')->get();

        return response()->json(['data' => $deliveries], 200, [], JSON_PRETTY_PRINT);
    }
}
