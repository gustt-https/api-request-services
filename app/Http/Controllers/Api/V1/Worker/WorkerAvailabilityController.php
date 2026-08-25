<?php

namespace App\Http\Controllers\Api\V1\Worker;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerAvailabilityRequest;
use App\Service\V1\worker\WorkerAvailabilityService;


class WorkerAvailabilityController extends Controller
{
    public function enable(WorkerAvailabilityRequest $request, WorkerAvailabilityService $workerService)
    {
        $worker = $request->user();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $workerService->enable($worker, $latitude, $longitude);

        return response()->json([
            'success' => true,
            'message' => 'Você está disponivel para receber novos serviços.'
        ]);
    }
}
