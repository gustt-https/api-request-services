<?php

namespace App\Http\Controllers\Api\V1\Worker;

use App\Http\Controllers\Controller;
use App\Models\RequestService;
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

    // Ajustado: a rota já apontava para cancel, mas o método não existia.
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
}
