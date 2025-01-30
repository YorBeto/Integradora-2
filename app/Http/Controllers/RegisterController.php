<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fingerprint' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required|string',
            'last_name' => 'required|string',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string',
            'role' => 'required|string|in:admin,owner,worker,user',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            DB::statement("CALL RegisterPerson(?, ?, ?, ?, ?, ?, ?, ?)", [
                $request->fingerprint,
                $request->email,
                Hash::make($request->password),
                $request->name,
                $request->last_name,
                $request->birth_date,
                $request->phone,
                $request->role
            ]);

            return response()->json(['message' => 'Usuario registrado correctamente'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar usuario', 'details' => $e->getMessage()], 500);
        }
    }
}
