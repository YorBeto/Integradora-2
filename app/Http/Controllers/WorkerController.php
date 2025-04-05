<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Person;
use app\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Invoice;


class WorkerController extends Controller
{
    public function index()
    {
        // Consulta la vista que contiene los workers
        $workers = DB::table('workers_view')->get();

        return response()->json(['data' => $workers], 200, [], JSON_PRETTY_PRINT);
    }

    public function show($id) // como este pero nuevo
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
                'workers.NSS',
                'users.activate',
            )
            ->first(); 

        if (!$worker) {
            return response()->json(['error' => 'No se encontró información de trabajador para este ID.'], 404);
        }

        return response()->json($worker);
    }

    public function update(Request $request, $id)
    {
        $worker = Worker::findOrFail($id);
        $person = Person::findOrFail($worker->person_id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u', // Permite letras y espacios
            'last_name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'phone' => 'required|string|max:20|regex:/^[0-9]+$/',
            'RFID' => [
                'required',
                'string',
                'max:50',
                Rule::unique('workers')->ignore($worker->id)
            ],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($person->user_id)
            ],
            'RFC' => [
                'sometimes',
                'string',
                'max:13',
                Rule::unique('workers')->ignore($worker->id)
            ],
            'NSS' => [
                'sometimes',
                'string',
                'max:11',
                Rule::unique('workers')->ignore($worker->id)
            ]
        ], [
            'RFID.unique' => 'Este RFID ya está registrado por otro trabajador',
            'email.unique' => 'Este correo ya está registrado',
            'phone.regex' => 'El teléfono solo debe contener números'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::transaction(function () use ($request, $person, $worker) {
                $person->update([
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone
                ]);

                $worker->update([
                    'RFID' => $request->RFID,
                    'RFC' => $request->RFC ?? $worker->RFC,
                    'NSS' => $request->NSS ?? $worker->NSS
                ]);

                if ($request->email) {
                    $person->user->update(['email' => $request->email]);
                }
            });

            return response()->json([
                'message' => 'Trabajador actualizado correctamente',
                'data' => $worker->load('person', 'person.user')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar trabajador',
                'details' => $e->getMessage()
            ], 500);
        }
    }    

    public function availableWorkers()
    {
        $maxOrders = 4;
        $workers = Worker::withCount(['invoices' => function ($query) {
            $query->where('status', 'Assigned');
        }])->having('invoices_count', '<', $maxOrders)->get();

        return response()->json($workers);
    }

    public function assignedInvoices($id)
    {
        $worker = Worker::find($id);

        if (!$worker) {
            return response()->json(['error' => 'Trabajador no encontrado.'], 404);
        }

        $invoices = Invoice::where('assigned_to', $id)
            ->where('status', 'Assigned')
            ->get();

        return response()->json($invoices);
    }
}
