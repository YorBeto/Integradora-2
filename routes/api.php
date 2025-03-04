<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountActivationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rutas protegidas con 'auth:sanctum'
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Registro
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/register-worker', [RegisterController::class, 'registerWorker']);

// Autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Restablecimiento de contraseña
Route::post('/send-reset-password-link', [AuthController::class, 'sendResetPasswordLink'])->middleware('auth:api');
Route::get('/password/reset/{user}', [AuthController::class, 'showResetPasswordForm'])
    ->name('password.reset.form')
    ->middleware('signed');

Route::post('/password/update', [AuthController::class, 'updatePassword'])->name('password.update');

Route::get('/password/success', function () {
    return view('auth.password_success');
})->name('password.success');

