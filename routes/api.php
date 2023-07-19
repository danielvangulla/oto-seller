<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::group(['middleware' => 'jwt.auth'], function () {
    Route::apiResource('/motor', MotorController::class);
    Route::apiResource('/mobil', MobilController::class);

    Route::apiResource('/kendaraan', KendaraanController::class)->only('index', 'show');
    Route::patch('/add-stock/{kendaraan_id}', [KendaraanController::class, 'addStock']);

    Route::apiResource('/sales', SaleController::class)->only('index', 'store', 'show');
});
