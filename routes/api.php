<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\SendVerificationCodeController;
use App\Http\Controllers\Api\V1\Auth\VerificationCodeController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\Identity\IdentityController;
use App\Http\Controllers\Api\V1\Requests\RequestController;
use App\Http\Controllers\Api\V1\Worker\WorkerAvailabilityController;
use App\Http\Controllers\Api\V1\Worker\WorkerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/auth/send-code', SendVerificationCodeController::class);
    Route::post('/auth/verify-code', VerificationCodeController::class);

    Route::post('client/auth/register', [AuthController::class, 'clientRegister'])->middleware('auth:sanctum', 'abilities:server:registration');
    Route::post('worker/auth/register', [AuthController::class, 'workerRegister'])->middleware('auth:sanctum', 'abilities:server:registration');

    Route::post('/devices', [DeviceController::class, 'register'])->middleware('auth:sanctum');

    Route::prefix('client')
        ->middleware(['auth:sanctum', 'client'])
        ->group(function () {
            Route::post('/requests', [RequestController::class, 'store']);
            Route::get('/requests/{requestService}', [RequestController::class, 'show']);
        });


    Route::prefix('worker')
        ->middleware(['auth:sanctum', 'worker'])
        ->group(function () {
            Route::get('/availability', [WorkerAvailabilityController::class, 'availability']);
            Route::post('/availability/enable', [WorkerAvailabilityController::class, 'enable']);
            Route::post('/availability/disabled', [WorkerAvailabilityController::class, 'disabled']);
            Route::get('/current-service', [WorkerController::class, 'current']);
            Route::post('/request/{requestService}/accept', [WorkerController::class, 'accept']);
            Route::post('/request/{requestService}/start', [WorkerController::class, 'start']);
            Route::post('/request/{requestService}/finish', [WorkerController::class, 'finish']);
            Route::post('/request/{requestService}/cancel', [WorkerController::class, 'cancel']);
            Route::get('/requests/{requestService}/preview', [RequestController::class, 'preview']);
            Route::get('/services', [WorkerController::class, 'index']);
            Route::post('/identity-verification', [IdentityController::class, 'submit']);
        });
});
