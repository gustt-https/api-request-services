<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\SendVerificationCodeController;
use App\Http\Controllers\Api\V1\Auth\VerificationCodeController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\Requests\RequestController;
use App\Http\Controllers\Api\V1\Worker\WorkerAvailabilityController;
use App\Http\Controllers\Api\V1\Worker\WorkerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/auth/send-code', SendVerificationCodeController::class);
    Route::post('/auth/verify-code', VerificationCodeController::class);

    Route::post('client/auth/register', [AuthController::class, 'clientRegister'])->middleware('auth:sanctum', 'abilities:server:registration');
    Route::post('worker/auth/register', [AuthController::class, 'workerRegister'])->middleware('auth:sanctum', 'abilities:server:registration');

    Route::middleware('auth:sanctum', 'abilities:server:access')->group(function () {
        Route::post('/devices', [DeviceController::class, 'register']);

        Route::post('/requests', [RequestController::class, 'store']);
        Route::get('/requests/{request}', [RequestController::class, 'show']);
        Route::get('/requests/{request}/worker/preview', [RequestController::class, 'preview']);
        

        Route::post('/worker/availability/enable', [WorkerAvailabilityController::class, 'enable']);
        Route::post('/worker/availability/disabled', [WorkerAvailabilityController::class, 'disabled']);
        Route::get('/worker/current-service', [WorkerController::class, 'current']);
        Route::post('/worker/request/{request}/accept', [WorkerController::class, 'accept']);
        Route::post('/worker/request/{request}/start', [WorkerController::class, 'start']);
        Route::post('/worker/request/{request}/finish', [WorkerController::class, 'finish']);
        Route::post('/worker/request/{request}/cancel', [WorkerController::class, 'cancel']);
    });
});
