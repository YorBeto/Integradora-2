<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountActivationController;
use App\Http\Controllers\InvoiceController;
use App\Models\Productsss;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/activate-account/{user}', [AccountActivationController::class, 'activateAccount'])
    ->name('activation.route')
    ->middleware('signed');

    Route::get('/reset-password/{email}', [AuthController::class, 'resetPassword'])
    ->name('reset-password.form')
    ->middleware('signed');

    //Route::get('/invoices/stream', [InvoiceController::class, 'generateInvoice']);

    Route::get('/test-mongo', function () {
        // Insertar un documento
        $product = Productsss::create([
            'name' => 'Laptop',
            'price' => 999.99,
            'description' => 'Una laptop de última generación',
        ]);
    
        return response()->json([
            'message' => 'Producto insertado en MongoDB',
            'data' => $product,
        ]);
    });