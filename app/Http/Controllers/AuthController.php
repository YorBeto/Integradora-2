<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
        $credentials = $request->only('email', 'password');
    
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }
    
        $role = $user->roles()->first();  
    
        $token = JWTAuth::claims([
            'user' => $user,
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

    public function sendResetPasswordLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'El campo "Correo Electrónico" es obligatorio.',
            'email.email' => 'El campo "Correo Electrónico" debe ser una dirección de correo válida.',
            'email.exists' => 'El correo electrónico no está registrado en el sistema.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::where('email', $request->email)->first();

        $resetPasswordLink = URL::temporarySignedRoute(
            'password.reset.form',
            now()->addMinutes(30),
            ['user' => $user->id]
        );

        Mail::to($user->email)->send(new ResetPasswordMail($user->name, $resetPasswordLink));

        return response()->json(['message' => 'Se ha enviado un enlace para restablecer la contraseña a tu correo electrónico.']);
    }

    public function showResetPasswordForm(Request $request, $user)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('login')->withErrors(['link' => 'El enlace para cambiar la contraseña es inválido o ha expirado.']);
        }
    
        // Inicializar errores si no existen en la sesión
        $errors = session('errors', new MessageBag());
    
        return view('auth.reset_password', [
            'user' => $user,
            'errors' => $errors
        ]);
    }

    public function updatePassword(Request $request)

    {

        $usuario = Auth::user();
        // Validar todos los campos necesarios
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ], [
            'current_password.required' => 'El campo "Contraseña Actual" es obligatorio.',
            'password.required' => 'El campo "Nueva Contraseña" es obligatorio.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);
        
        // Redireccionar con errores si la validación falla
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Buscar el usuario por ID
        $user = User::find($request->user);
    
        if (!$user) {
            return redirect()->back()->withErrors(['user' => 'Usuario no encontrado.']);
        }
    
        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'La contraseña actual no es correcta.'])->withInput();
        }
    
        // Actualizar la contraseña
        $user->password = Hash::make($request->password);
        $user->save();
    
        // Redirigir a la vista de éxito
        return redirect()->route('password.success');
    }   
    
    
}