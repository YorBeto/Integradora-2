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

// Autenticación
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    // Auth
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Registro
    Route::post('/register', [RegisterController::class, 'registerWorker']);

    // Workers
    Route::get('/workers', [WorkerController::class, 'index']);
    Route::get('/worker', [WorkerController::class, 'show']);
    Route::put('/worker/{id}', [WorkerController::class, 'update']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);

    // Facturas y ordenes
    Route::get('/invoice', [InvoiceController::class, 'generateInvoice']);
    Route::middleware('auth:api')->get('/invoices', [InvoiceController::class, 'getInvoices']);
});

