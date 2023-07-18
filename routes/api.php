<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\MotorController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::group(['middleware' => 'jwt.auth'], function () {
    Route::apiResource('motor', MotorController::class);
    Route::apiResource('mobil', MobilController::class);
});
