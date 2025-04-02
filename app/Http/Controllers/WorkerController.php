<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Invoice;


class WorkerController extends Controller
{
    public function index()
    {
        $workers = DB::table('workers')
            ->join('people', 'workers.person_id', '=', 'people.id')
            ->join('users', 'people.user_id', '=', 'users.id')  
            ->select(
                'workers.id',
                'users.email',
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

    public function show($id)
    {
        $worker = DB::table('workers')
            ->join('people', 'workers.person_id', '=', 'people.id')
            ->where('workers.id', $id)
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
            return response()->json(['error' => 'No se encontró información de trabajador para este ID.'], 404);
        }

        return response()->json($worker);
    }

    public function update(Request $request, $id)
    {
        // Validar los datos entrantes
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'RFID' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Buscar el trabajador
        $worker = Worker::find($id);

        if (!$worker) {
            return response()->json(['error' => 'Trabajador no encontrado.'], 404);
        }

        // Actualizar solo los campos permitidos
        $person = Person::find($worker->person_id);
        
        if (!$person) {
            return response()->json(['error' => 'Persona asociada no encontrada.'], 404);
        }

        $person->name = $request->input('name');
        $person->last_name = $request->input('last_name');
        $person->phone = $request->input('phone');
        $person->save();

        $worker->RFID = $request->input('RFID');
        $worker->save();

        return response()->json(['message' => 'Trabajador actualizado correctamente.'], 200);
    }

        public function getAvailableWorkers()
        {
            $maxOrders = 4;
            $workers = Worker::withCount(['invoices' => function ($query) {
                $query->where('status', 'Assigned');
            }])->having('invoices_count', '<', $maxOrders)->get();

            return response()->json($workers);
        }

        public function getAssignedInvoices($workerId)
        {
            $worker = Worker::find($workerId);

            if (!$worker) {
                return response()->json(['error' => 'Trabajador no encontrado.'], 404);
            }

            $invoices = Invoice::where('assigned_to', $workerId)
                ->where('status', 'Assigned') 
                ->get();

            return response()->json($invoices);
        }

}
