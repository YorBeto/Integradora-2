<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class WorkerController extends Controller
{
    public function index()
    {
        $workers = DB::table('workers')
            ->join('people', 'workers.person_id', '=', 'people.id')
            ->select(
                'workers.id',
                'people.name',
                'people.last_name',
                'people.birth_date',
                DB::raw('TIMESTAMPDIFF(YEAR, people.birth_date, CURDATE()) as age'),
                'people.phone',
                'workers.RFID',
                'workers.RFC',
                'workers.NSS'
            )
            ->get();

        return response()->json($workers);
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'El ID de usuario es requerido y debe existir en la base de datos.'], 400);
        }

        $userId = $request->input('id');

        $worker = DB::table('workers')
            ->join('people', 'workers.person_id', '=', 'people.id')
            ->where('people.user_id', $userId)
            ->select(
                'workers.id',
                'people.name',
                'people.last_name',
                'people.birth_date',
                DB::raw('TIMESTAMPDIFF(YEAR, people.birth_date, CURDATE()) as age'),
                'people.phone',
                'workers.RFID',
                'workers.RFC',
                'workers.NSS'
            )
            ->first(); 

        if (!$worker) {
            return response()->json(['error' => 'No se encontró información de trabajador para este usuario.'], 404);
        }

        return response()->json($worker);
    }

}
