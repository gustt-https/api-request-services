<?php

namespace App\Http\Controllers\Api\V1\Requests;

use App\Http\Controllers\Controller;
use App\Http\Requests\CancelRequestByClient;
use App\Http\Requests\RequestServiceRequest;
use App\Http\Resources\RequestResource;
use App\Http\Resources\RequestResourcePreview;
use App\Models\Request;
use App\Service\V1\requests\CancelRequest;
use App\Service\V1\requests\CurrentRequest;
use App\Service\V1\requests\GetWorkerLocation;
use App\Service\V1\requests\RequestService;
use App\Service\V1\requests\ShowRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{

    use AuthorizesRequests;

    public function index(
        ShowRequests $requests
    ) {
        $client = Auth::user();
        $data = $requests->execute($client);

        return response()->json([
            'data' => $data
        ]);
    }

    public function show(
        Request $requestService
    ) {
        $this->authorize('view', $requestService);

        $requestService->load(['worker.workerProfile', 'securityCode', 'medias']);

        return response()->json([
            'data' => new RequestResource($requestService)
        ]);
    }

    public function preview(
        Request $requestService
    ) {
        $this->authorize('preview', $requestService);

        return response()->json([
            'data' => new RequestResourcePreview($requestService->load(['medias']))
        ]);
    }

    public function store(
        RequestServiceRequest $request,
        RequestService $service
    ) {
        $user = $request->user();
        $payload = $request->validated();

        $newRequest =  $service->makeRequest($user, $payload);

        return response()->json([
            'success' => true,
            'message' => 'Solicitação criada',
            'data' => $newRequest
        ], 201);
    }

    public function cancel(
        Request $requestService,
        CancelRequest $service,
        CancelRequestByClient $request
    ) {

        $this->authorize('cancelByClient', $requestService);

        $reason = $request->input('reason');
        $data = $service->execute($requestService, $reason);

        return response()->json([
            'success' => true,
            'message' => 'Solicitação cancelada com sucesso',
            'data' => $data
        ]);
    }

    public function current(
        CurrentRequest $current
    ) {

        $client = Auth::user();
        $data = $current->execute($client);

        return response()->json([
            'data' => $data
        ]);
    }

    public function workerLocation(
        Request $requestService,
        GetWorkerLocation $workerLocation
    ) {
        $this->authorize('workerLocation', $requestService);
        $data = $workerLocation->execute($requestService);

        return response()->json([
            'data' => $data
        ]);
    }
}
