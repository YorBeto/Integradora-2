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

class RegisterController extends Controller
{
    public function registerWorker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'RFID' => 'nullable|string|max:255',
            'RFC' => 'nullable|string|max:255',
            'NSS' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $randomPassword = Str::random(10); // Generar una contraseña aleatoria
        $hashedPassword = Hash::make($randomPassword);

        try {
            // Insertar usuario con rol de "user" (role_id = 3)
            DB::statement("CALL RegisterWorker(?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                $request->email,
                $hashedPassword,
                $request->name,
                $request->last_name,
                $request->birth_date,
                $request->phone,
                $request->RFID,
                $request->RFC,
                $request->NSS
            ]);

            // Generar enlace de activación con email codificado
            $encodedEmail = base64_encode($request->email);
            $activationLink = url('/activate-account?email=' . $encodedEmail);

            // Enviar correo con el enlace de activación y la contraseña generada
            Mail::to($request->email)->send(new AccountActivationMail($request->name, $activationLink, $randomPassword));

            return response()->json(['message' => 'Trabajador registrado exitosamente'], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar trabajador', 'details' => $e->getMessage()], 500);
        }
    }
}
