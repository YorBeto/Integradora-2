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
}
