<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;

class AccountActivationController extends Controller
{
        public function activateAccount(Request $request, $user)
    {
        $user = User::findOrFail($user);
        $user->email_verified_at = now();
        $user->save();

        return redirect()->to('https://jmrhmrrj-4200.usw3.devtunnels.ms/forget-password');
    }
    
}
