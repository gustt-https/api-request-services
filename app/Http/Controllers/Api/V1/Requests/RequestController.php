<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestServiceRequest;
use App\Http\Resources\RequestResource;
use App\Service\V1\requests\RequestService;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{

    public function show(RequestService $request)
    {
        return response()->json([
            'data' => new RequestResource($request)
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
