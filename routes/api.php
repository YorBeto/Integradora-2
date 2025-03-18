<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountActivationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ProductController;


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
Route::post('/register-worker', [RegisterController::class, 'registerWorker']);

// Autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Restablecimiento de contraseña
Route::middleware('auth:api')->post('/send-reset-password-link', [AuthController::class, 'sendResetPasswordLink']);
Route::get('/password/reset/{user}', [AuthController::class, 'showResetPasswordForm'])
    ->name('password.reset.form')
    ->middleware('signed');

Route::post('/password/update', [AuthController::class, 'updatePassword'])->name('password.update');



Route::get('/generate-invoice', [InvoiceController::class, 'generateInvoice']);
Route::get('/invoices', [InvoiceController::class, 'getInvoices']);

Route::get('/workers', [WorkerController::class, 'index']);
Route::get('/worker', [WorkerController::class, 'worker']);

Route::get('/products', [ProductController::class, 'index']);

