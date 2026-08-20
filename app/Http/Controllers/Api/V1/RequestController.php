<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestServiceRequest;
use App\Service\V1\requests\RequestService;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{

    public function store(RequestServiceRequest $request, RequestService $service)
    {
        $user = Auth::user();
        $payload = $request->validated();

        $service->makeRequest($user, $payload);

        return response()->json([
            'success' => true,
            'message' => 'Solicitação criada',
        ], 200);
    }
}
