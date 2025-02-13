<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;

class AccountActivationController extends Controller
{
    public function activateAccount(Request $request)
    {
        // Decodificar el email desde la URL
        $email = base64_decode($request->query('email'));

        // Verificar si el usuario existe
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'El usuario no existe o el enlace es inválido'], 404);
        }

        // Verificar si el usuario ya está activado
        if ($user->email_verified_at !== null) {
            return response()->json(['message' => 'La cuenta ya está activada'], 200);
        }

        try {
            DB::beginTransaction();

            // Actualizar el campo email_verified_at con la fecha actual y cambiar el rol a worker
            $user->update([
                'email_verified_at' => Carbon::now(),
            ]);

            // Cambiar el rol del usuario a "worker" (role_id = 2)
            DB::table('role_user')
                ->where('user_id', $user->id)
                ->update(['role_id' => 2]);

            DB::commit();

            return response()->json(['message' => 'Cuenta activada con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al activar la cuenta', 'details' => $e->getMessage()], 500);
        }
    }
}
