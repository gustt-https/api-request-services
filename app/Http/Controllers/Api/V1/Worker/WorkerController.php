<?php

namespace App\Http\Controllers\Api\V1\Worker;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerStartService;
use App\Models\Request;
use App\Service\V1\worker\GetCurrentWorkerService;
use App\Service\V1\worker\WorkerAcceptRequestService;
use App\Service\V1\worker\WorkerCancelRequestService;
use App\Service\V1\worker\WorkerCompletService;
use App\Service\V1\worker\WorkerFinishRequestService;
use App\Service\V1\worker\WorkerStartRequestService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    use AuthorizesRequests;

    public function index(
        WorkerCompletService $workerService
    ) {
        $worker = Auth::user();
        $completed = $workerService->execute($worker);

        return $completed;
    }

    public function accept(
        Request $requestService,
        WorkerAcceptRequestService $workerService
    ) {
        $worker = Auth::user();
        $acceptedRequest = $workerService->acceptRequest($requestService, $worker);

        return response()->json([
            'success' => true,
            'message' => 'Requisição aceita com sucesso.',
            'data' => $acceptedRequest
        ]);
    }

    public function start(
        Request $requestService,
        WorkerStartService $request,
        WorkerStartRequestService $workerService
    ) {

        $this->authorize('start', $requestService);

        $worker = $request->user();
        $code   = $request->input('code');

        $startedRequest = $workerService->execute($requestService, $worker, $code);

        return response()->json([
            'success' => true,
            'message' => 'O serviço foi iniciado.',
            'data' => $startedRequest
        ]);
    }

    public function finish(
        Request $requestService,
        WorkerFinishRequestService $workerService
    ) {

        $this->authorize('finish', $requestService);

        $worker = Auth::user();
        $finishedRequest = $workerService->execute($requestService, $worker);

        return response()->json([
            'success' => true,
            'message' => 'Serviço encerrado.',
            'data' => $finishedRequest
        ]);
    }

    public function cancel(
        Request $requestService,
        WorkerCancelRequestService $workerService
    ) {

        $this->authorize('cancel', $requestService);

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
