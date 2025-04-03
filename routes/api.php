<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountActivationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\LightSensorController;
use App\Http\Controllers\TemperatureHumiditySensorController;


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
    Route::post('/logout', [AuthController::class, 'logout']); //
    Route::post('/update-password', [AuthController::class, 'updatePassword']); // Ready
});

    // Registro
    Route::post('/register', [RegisterController::class, 'registerWorker']);

    // Workers
    Route::get('/workers', [WorkerController::class, 'index']);
    Route::get('/workers/invoices', [WorkerController::class, 'getAvailableWorkers']);
    Route::get('/worker/{id}', [WorkerController::class, 'show']);
    Route::put('/worker/{id}', [WorkerController::class, 'update']);

    Route::put('/user/desactivate', [AuthController::class, 'desactivateAccount']);
    Route::put('/user/activate', [AuthController::class, 'activateAccount']);

    // Facturas y ordenes
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('/invoice', [InvoiceController::class, 'generateInvoice']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);

    // Devices
    Route::get('/divice', [DeviceController::class, 'index']);
    Route::post('/device', [DeviceController::class, 'store']);
    Route::get('/device/{id}', [DeviceController::class, 'show']);
    Route::put('/device/{id}', [DeviceController::class, 'update']);

    // Deliveries
    Route::get('/deliveries', [DeliveryController::class, 'index']);

    Route::middleware('auth:api')->get('/my-deliveries', [DeliveryController::class, 'show']);

    Route::post('invoices/{invoiceId}/assign', [InvoiceController::class, 'assignInvoice']);

    Route::middleware('auth:api')->post('deliveries/{deliveryId}/complete', [DeliveryController::class, 'completeDelivery']);
    Route::get('workers/{workerId}/invoices', [WorkerController::class, 'getAssignedInvoices']);

    // Light Sensor
    Route::get('/light-sensor', [LightSensorController::class, 'getLastLightStatus']);
    Route::get('/temperature-humidity-sensor', [TemperatureHumiditySensorController::class, 'getLastTemperatureHumidityStatus']);