<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Person;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\MessageBag;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'El campo "Correo Electrónico" es obligatorio.',
            'email.email' => 'El campo "Correo Electrónico" debe ser una dirección de correo válida.',
            'password.required' => 'El campo "Contraseña" es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
    
        $user = User::where('email', $credentials['email'])->first();
    
        
        if (!$user->activate) {
            return response()->json(['error' => 'Cuenta desactivada'], 403);
        }
    
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }
    
        $role = $user->roles()->first();  
    
        $token = JWTAuth::claims([
            'user' => $user,
            'role' => $role ? $role->name : null 
            ])->fromUser($user);
            
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'role' => $role ? $role->name : null,  
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ]);
    }

    public function logout()
    {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
    
        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
    
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ], [
            'current_password.required' => 'El campo "Contraseña Actual" es obligatorio.',
            'password.required' => 'El campo "Nueva Contraseña" es obligatorio.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    
        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual no es correcta.'
            ], 401);
        }
    
        // Actualizar la contraseña
        $user->password = Hash::make($request->password);
        $user->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente.'
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    
        // Obtener el nombre desde la tabla 'people' usando la relación definida en el modelo
        $userName = $user->person ? $user->person->name : 'Usuario';
    
        // Generar una nueva contraseña aleatoria
        $newPassword = Str::random(10);
    
        // Actualizar la contraseña en la base de datos
        $user->password = Hash::make($newPassword);
        $user->save();
    
        // Enviar la nueva contraseña por correo
        Mail::to($user->email)->send(new ResetPasswordMail($userName, $newPassword));
    
        return response()->json(['message' => 'Se ha enviado una nueva contraseña a su correo.']);
    }     

    public function desactivateAccount(Request $request)
    {
        // Validar que se envíe el ID del trabajador
        $request->validate([
            'id' => 'required|integer|exists:workers,id',
        ]);
    
        // Buscar el trabajador en la tabla 'workers' por su ID
        $worker = Worker::find($request->id);
    
        // Verificar si el trabajador existe
        if (!$worker) {
            return response()->json(['message' => 'Trabajador no encontrado.'], 404);
        }
    
        // Buscar el usuario asociado a través del 'person_id' en 'workers' y 'user_id' en 'people'
        $user = User::whereHas('person', function($query) use ($worker) {
            $query->where('id', $worker->person_id);
        })->first();
    
        // Verificar si el usuario existe
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }
    
        // Verificar si la cuenta ya está desactivada
        if (!$user->activate) {
            return response()->json(['message' => 'La cuenta ya está desactivada.'], 400);
        }
    
        // Desactivar la cuenta
        $user->activate = false;
        $user->save();
    
        return response()->json(['message' => 'Cuenta desactivada correctamente.'], 200);
    }    

    public function activateAccount(Request $request)
    {
        // Validar que se envíe el ID del trabajador
        $request->validate([
            'id' => 'required|integer|exists:workers,id',
        ]);

        // Buscar el trabajador en la tabla 'workers' por su ID
        $worker = Worker::find($request->id);

        // Verificar si el trabajador existe
        if (!$worker) {
            return response()->json(['message' => 'Trabajador no encontrado.'], 404);
        }

        // Buscar el usuario asociado a través del 'person_id' en 'workers' y 'user_id' en 'people'
        $user = User::whereHas('person', function($query) use ($worker) {
            $query->where('id', $worker->person_id);
        })->first();

        // Verificar si el usuario existe
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        // Verificar si la cuenta ya está activada
        if ($user->activate) {
            return response()->json(['message' => 'La cuenta ya está activada.'], 400);
        }

        // Activar la cuenta
        $user->activate = true;
        $user->save();

        return response()->json(['message' => 'Cuenta activada correctamente.'], 200);
    }
}