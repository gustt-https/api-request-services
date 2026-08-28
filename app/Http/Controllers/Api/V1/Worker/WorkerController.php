<?php

namespace App\Http\Controllers\Api\V1\Worker;

use App\Http\Controllers\Controller;
use App\Models\RequestService;
use App\Service\V1\worker\GetCurrentWorkerService;
use App\Service\V1\worker\WorkerAcceptRequestService;
use App\Service\V1\worker\WorkerCancelRequestService;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public function accept(RequestService $requestService, WorkerAcceptRequestService $workerService)
    {
        $worker = Auth::user();
        $acceptedRequest = $workerService->acceptRequest($requestService, $worker);

        return response()->json([
            'success' => true,
            'message' => 'Requisição aceita com sucesso.',
            'data' => $acceptedRequest
        ]);
    }

    public function cancel(RequestService $requestService, WorkerCancelRequestService $workerService)
    {
        $worker = Auth::user();
        $cancelledRequest = $workerService->cancelRequest($requestService, $worker);

        return response()->json([
            'success' => true,
            'message' => 'Requisição cancelada com sucesso.',
            'data' => $cancelledRequest
        ]);
    }

    public function current(GetCurrentWorkerService $CurrentWorkerService)
    {
        $worker = Auth::user();

        return response()->json([
            'data' => $CurrentWorkerService->execute($worker)
        ]);
    }
}
