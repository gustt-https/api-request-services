<?php

namespace App\Http\Controllers\Api\V1\Worker;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerAvailabilityRequest;
use App\Service\V1\worker\WorkerAvailabilityService;
use Illuminate\Http\Request;


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

    // Ajustado: a rota já apontava para disabled, mas o método não existia.
    public function disabled(Request $request, WorkerAvailabilityService $workerService)
    {
        $workerService->disabled($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Você não está mais disponível para receber novos serviços.'
        ]);
    }
}
