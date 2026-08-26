<?php

namespace App\Http\Controllers\Api\V1\Requests;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestServiceRequest;
use App\Http\Resources\RequestResource;
use App\Models\RequestService as RequestServiceModel;
use App\Service\V1\requests\RequestService;

class RequestController extends Controller
{
    // Ajustado: type-hint no model (alias) — RequestService sozinho era o service, não o registro.
    public function show(RequestServiceModel $requestService)
    {
        return response()->json([
            'data' => new RequestResource($requestService)
        ]);
    }

    public function store(RequestServiceRequest $request, RequestService $service)
    {
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
