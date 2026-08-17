<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {

    Route::post('/auth/send-code', [AuthController::class, 'send']);
    Route::post('/auth/verify-code', [AuthController::class, 'verify']);
    Route::post('/auth/register', [AuthController::class, 'register'])->middleware('auth:sanctum', 'abilities:server:registration');


    Route::middleware('auth:sanctum', 'abilities:server:access')->group(function() {
        Route::post('/requests', [RequestController::class, 'store']);
    });
});