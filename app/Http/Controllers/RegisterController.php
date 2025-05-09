<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\AccountActivationMail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use App\Models\Person;
use App\Models\Worker;

class RegisterController extends Controller
{
    public function registerWorker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->whereNull('deleted_at') // Ignorar registros eliminados
            ],
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('people', 'phone')->whereNull('deleted_at') // Ignorar registros eliminados
            ],
            'RFID' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('workers', 'RFID')->whereNull('deleted_at') // Ignorar registros eliminados
            ],
            'RFC' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('workers', 'RFC')->whereNull('deleted_at') // Ignorar registros eliminados
            ],
            'NSS' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('workers', 'NSS')->whereNull('deleted_at') // Ignorar registros eliminados
            ]
        ], [
            'email.unique' => 'El correo electrónico ya está registrado.',
            'phone.unique' => 'El teléfono ya está registrado.',
            'RFID.unique' => 'El RFID ya está registrado.',
            'RFC.unique' => 'El RFC ya está registrado.',
            'NSS.unique' => 'El NSS ya está registrado.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $randomPassword = Str::random(10); 
        $hashedPassword = Hash::make($randomPassword);
        $profile_photo = 'https://equiposikra.s3.us-east-2.amazonaws.com/Profile-images/workerimage.jpeg';

        try {
            // Iniciar una transacción
            DB::beginTransaction();

            DB::statement("CALL RegisterWorker(?, ?, ?, ?, ?, ?, ?, ?, ?,?)", [
                $request->email,
                $hashedPassword,
                $profile_photo,
                $request->name,
                $request->last_name,
                $request->birth_date,
                $request->phone,
                $request->RFID,
                $request->RFC,
                $request->NSS
            ]);

            // Obtener el usuario recién creado
            $user = User::where('email', $request->email)->firstOrFail();

            $person = $user->person; 

            if (!$person) {
                // Si no se encuentra la persona, lanzar una excepción
                throw new \Exception('No se encontró información de persona para este usuario.');
            }

            $activationLink = URL::temporarySignedRoute(
                'activation.route',
                now()->addMinutes(30),
                ['user' => $user->id]
            );

            Mail::to($user->email)->send(new AccountActivationMail($person->name, $activationLink, $randomPassword));

            // Confirmar la transacción
            DB::commit();

            return response()->json(['message' => 'Trabajador registrado exitosamente'], 201);

        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            return response()->json(['error' => 'Error al registrar trabajador', 'details' => $e->getMessage()], 500);
        }
    }

    public function resendActivationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
    
        try {
            $user = User::where('email', $request->email)->firstOrFail();
            $person = $user->person;
    
            if (!$person) {
                throw new \Exception('No se encontró información de persona para este usuario.');
            }
    
            $newPassword = Str::random(10);
            $user->password = Hash::make($newPassword);
            $user->save();
    
            $activationLink = URL::temporarySignedRoute(
                'activation.route',
                now()->addMinutes(60),
                ['user' => $user->id]
            );
    
            Mail::to($user->email)->send(new AccountActivationMail($person->name, $activationLink, $newPassword));
    
            return response()->json(['message' => 'Correo de activación reenviado con nueva contraseña.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo reenviar el correo', 'details' => $e->getMessage()], 500);
        }
    }    
}

