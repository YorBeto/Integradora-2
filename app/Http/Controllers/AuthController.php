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
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;



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

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Crear un enlace firmado para el restablecimiento de la contraseña
        $resetLink = URL::temporarySignedRoute(
            'reset-password.form',
            now()->addMinutes(30),  // Caduca después de 30 minutos
            ['email' => base64_encode($user->email)]  // El email codificado en base64
        );

        // Aquí rediriges al frontend. Cambia la URL por la de tu aplicación frontend
        $frontendUrl = 'https://mi-aplicacion-frontend.com/reset-password?token=' . urlencode($resetLink);

        // Enviar correo con la liga para cambiar la contraseña
        Mail::to($user->email)->send(new PasswordResetMail($frontendUrl));

        return response()->json(['message' => 'Se ha enviado el enlace para restablecer tu contraseña.'], 200);
    }


    public function resetPassword(Reques $request){
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
            'confirmed_password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        
        $user->password = Hash::make($request->password);
        $user->save();
    }



}
