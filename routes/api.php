<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\RequestController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/auth/send-code', [AuthController::class, 'send']);
    Route::post('/auth/verify-code', [AuthController::class, 'verify']);

    // Rotas de Registro/Auth
    Route::post('client/auth/register', [AuthController::class, 'clientRegister'])->middleware('auth:sanctum', 'abilities:server:registration');
    Route::post('workerk/auth/register', [AuthController::class, 'workerRegister'])->middleware('auth:sanctum', 'abilities:server:registration');




    Route::middleware('auth:sanctum', 'abilities:server:access')->group(function () {
        Route::post('/devices', [DeviceController::class, 'register']);
        Route::post('/requests', [RequestController::class, 'store']);
    });
});
