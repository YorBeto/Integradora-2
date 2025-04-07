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
use App\Http\Controllers\PirController;
use App\Http\Controllers\WeightSensorController;
use App\Http\Controllers\SensorsController;
use App\Http\Controllers\LockSensorController;
use App\Http\Controllers\RfidSensorController;

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

// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/resend-email', [RegisterController::class, 'resendActivationEmail']);

// Protected Routes
Route::middleware(['auth:api'])->group(function () {

    // Auth Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user/desactivate', [AuthController::class, 'desactivateAccount']);
    Route::put('/user/activate', [AuthController::class, 'activateAccount']);
    Route::put('/update-password', [AuthController::class, 'updatePassword']);

    // Registration Routes
    Route::post('/register', [RegisterController::class, 'registerWorker']);

    // Worker Routes
    Route::get('/workers', [WorkerController::class, 'index']);
    Route::get('/workers/invoices', [WorkerController::class, 'availableWorkers']);
    Route::get('/worker/{id}', [WorkerController::class, 'show']);
    Route::get('/worker-data', [WorkerController::class, 'getWorkerData']);
    Route::put('/worker/{id}', [WorkerController::class, 'update']);
    Route::delete('/worker/{id}', [WorkerController::class, 'destroy']);
    Route::get('/workers/{id}/invoices', [WorkerController::class, 'assignedInvoices']);

    // Invoice Routes
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('/invoice', [InvoiceController::class, 'generateInvoice']);
    Route::post('/invoices/{id}/assign', [InvoiceController::class, 'assignInvoice']);

    // Product Routes
    Route::get('/products', [ProductController::class, 'index']);

    // Delivery Routes
    Route::get('/deliveries', [DeliveryController::class, 'index']);
    Route::get('/my-deliveries', [DeliveryController::class, 'show']);
    Route::post('/delivery/{id}/complete', [DeliveryController::class, 'completeDelivery']);
});

// Update Stock
Route::put('/product/stock', [ProductController::class, 'stock']);

// Sensor Routes
Route::get('/temperature', [SensorsController::class, 'lastTemperature']);

// Devices
Route::get('/device', [DeviceController::class, 'index']);
Route::post('/device', [DeviceController::class, 'store']);
Route::get('/device/{id}', [DeviceController::class, 'show']);
Route::put('/device/{id}', [DeviceController::class, 'update']);

// Sanctum Protected Route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
    // Light Sensor
    Route::get('/light-sensor', [LightSensorController::class, 'getLastLightStatus']);

    // Temperature and Humidity Sensor
    Route::get('/temperature-humidity-sensor', [TemperatureHumiditySensorController::class, 'getLastTemperatureHumidityStatus']);

    // PIR Sensor
    Route::get('/pir-sensor', [PirController::class, 'getLastPirStatus']);

    // Weight Sensor
    Route::get('/weight-sensor', [WeightSensorController::class, 'lastRegisters']);

    //Rfid Sensor
    Route::get('/rfid-sensor', [RfidSensorController::class, 'getAll']);
    Route::get('/rfid-codes', [RfidSensorController::class, 'getAllRfidCodes']);
    Route::get('/assigned-rfid-codes', [RfidSensorController::class, 'getAssignedRfidCodes']);

    //get areas
    Route::get('/areas', [DeviceController::class, 'getAreas']);

    Route::get('/sensors/area/{area_id}', [SensorsController::class, 'sensoresporarea']);

    Route::get('/light-sensor/trigger-update', [LightSensorController::class, 'triggerLightUpdate']);
    Route::get('/temperature-humidity-sensor/trigger-update', [TemperatureHumiditySensorController::class, 'triggerThUpdate']);
    Route::get('/pir-sensor/trigger-update', [PirController::class, 'triggerPirUpdate']);
    Route::get('/weight-sensor/trigger-update', [WeightSensorController::class, 'triggerLastRegisters']);
    Route::get('/rfid-sensor/trigger-update', [LockSensorController::class, 'triggerLockUpdate']);

    Route::get('/lock-sensor/ultimos-accesos', [LockSensorController::class, 'ultimosAccesos']);