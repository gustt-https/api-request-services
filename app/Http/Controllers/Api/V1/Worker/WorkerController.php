<?php

namespace App\Http\Controllers\Api\V1\Worker;

use App\Http\Controllers\Controller;
use App\Models\RequestService;
use App\Service\V1\worker\WorkerAcceptRequestService;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public function accept(RequestService $request, WorkerAcceptRequestService $workerService)
    {
        $worker = Auth::user();
        $acceptedRequest = $workerService->acceptRequest($request, $worker);

        return response()->json([
            'success' => true,
            'message' => 'Requisição aceita com sucesso.',
            'data' => $acceptedRequest
        ]);
    }
}
