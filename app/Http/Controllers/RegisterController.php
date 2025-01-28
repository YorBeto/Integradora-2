<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request){

        $validator=Validator::make(request()->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $user=User::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'password'=>Hash::make(request('password')),
        ]);
        return response()->json($user,201);
    }
}
