<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountActivationController;

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