<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['apikey.verify']], function () {
    Route::resource('type-vehicles', \App\Http\Controllers\Api\TypeVehicleController::class);
    Route::resource('vehicles', \App\Http\Controllers\Api\VehicleController::class);
    Route::resource('vehicle-record', \App\Http\Controllers\Api\VehicleRecordController::class);
    Route::post('vehicle-record/report', [\App\Http\Controllers\Api\VehicleRecordController::class, 'report'])->name('vehicle-record.report');

});
