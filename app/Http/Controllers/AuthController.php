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

    public function forgotPassword(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'No se encontró un usuario con ese correo.'], 404);
    }

    // Generar token único
    $token = Str::random(60);

    DB::table('password_resets')->updateOrInsert(
        ['email' => $request->email],
        [
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]
    );

    
    $resetLink = url("/verify-reset-link?token={$token}&email={$request->email}");

    \Mail::send('emails.reset-password', ['resetLink' => $resetLink], function ($message) use ($request) {
    $message->to($request->email);
    $message->subject('Recuperación de contraseña');
    });


    return response()->json(['message' => 'Se ha enviado un enlace de recuperación a tu correo.']);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'token' => 'required',
        'password' => 'required|min:6|confirmed',
    ]);

    $record = DB::table('password_resets')->where('email', $request->email)->first();

    if (!$record || !Hash::check($request->token, $record->token)) {
        return response()->json(['message' => 'Token inválido o expirado'], 400);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    // Actualizar la contraseña
    $user->password = Hash::make($request->password);
    $user->save();

    // Eliminar el token usado
    DB::table('password_resets')->where('email', $request->email)->delete();

    return response()->json(['message' => 'Contraseña restablecida con éxito.']);
}
public function verifyResetLink(Request $request)
{
    // Validamos que los parámetros token y email estén presentes
    $request->validate([
        'token' => 'required|string',
        'email' => 'required|email',
    ]);

    // Verificamos si el token es válido para el correo proporcionado
    $record = DB::table('password_resets')->where('email', $request->email)->first();

    if (!$record || !Hash::check($request->token, $record->token)) {
        return response()->json(['message' => 'Token inválido o expirado'], 400);
    }

    // Si el token es válido, devolveremos un status 200 con el email y token
    return response()->json([
        'message' => 'Token verificado con éxito',
        'email' => $request->email,
        'token' => $request->token
    ], 200);
}




}
