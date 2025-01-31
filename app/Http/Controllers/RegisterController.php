<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Mail\AccountActivationMail;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required|string',
            'last_name' => 'required|string',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            DB::statement("CALL RegisterUser(?, ?, ?, ?, ?, ?, ?)", [
                $request->fingerprint,
                $request->email,
                Hash::make($request->password),
                $request->name,
                $request->last_name,
                $request->birth_date,
                $request->phone
            ]);

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado.'], 404);
            }

            $activationLink = URL::temporarySignedRoute(
                'activation.route',
                now()->addMinutes(60),
                ['user' => $user->id]
            );

            Mail::to($user->email)->send(new AccountActivationMail($user, $activationLink));

            return response()->json([
                'message' => 'Usuario registrado correctamente. Revisa tu correo para activar tu cuenta.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar usuario', 'details' => $e->getMessage()], 500);
        }
    }
}
