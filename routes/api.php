<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\RequestController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    // Rotas registro client
    Route::post('client/auth/send-code', [AuthController::class, 'send']);
    Route::post('client/auth/verify-code', [AuthController::class, 'verify']);
    Route::post('client/auth/register', [AuthController::class, 'register'])->middleware('auth:sanctum', 'abilities:server:registration');

    // Rotas registro Worker
    // Implementar futuramente


    Route::middleware('auth:sanctum', 'abilities:server:access')->group(function() {
        Route::post('/devices', [DeviceController::class, 'register']);



        Route::post('/requests', [RequestController::class, 'store']);
    });
});