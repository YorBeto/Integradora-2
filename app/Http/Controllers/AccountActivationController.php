<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AccountActivationController extends Controller
{
public function activateAccount(Request $request, $userId)
{
    $user = User::find($userId);

    if (!$user) {
        return response()->json(['error' => 'Usuario no encontrado.'], 404);
    }

    if ($user->email_verified_at) {
        return response()->json(['message' => 'La cuenta ya está activada.'], 200);
    }

    $user->email_verified_at = now();
    $user->save();

    $userRole = Role::where('name', 'user')->first();

    if (!$userRole) {
        return response()->json(['error' => 'Rol "user" no encontrado.'], 500);
    }

    DB::table('roles_users')
        ->where('user_id', $user->id)
        ->delete();

    DB::table('roles_users')->insert([
        'user_id' => $user->id,
        'rol_id' => $userRole->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['message' => 'Cuenta activada y rol actualizado a "user".'], 200);
}

}
