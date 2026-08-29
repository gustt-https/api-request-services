<?php

namespace App\Http\Controllers\Api\V1\Requests;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestServiceRequest;
use App\Http\Resources\RequestResource;
use App\Http\Resources\RequestResourcePreview;
use App\Models\Request;
use App\Service\V1\requests\RequestService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RequestController extends Controller
{

    use AuthorizesRequests;

    public function show(
        Request $requestService
    ) {
        $this->authorize('view', $requestService);

        return response()->json([
            'data' => new RequestResource($requestService)
        ]);
    }

    public function preview(
        Request $requestService
    ) {
        $this->authorize('preview', $requestService);

        return response()->json([
            'data' => new RequestResourcePreview($requestService)
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
}
