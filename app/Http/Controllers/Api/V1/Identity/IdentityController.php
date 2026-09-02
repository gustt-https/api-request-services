<?php

namespace App\Http\Controllers\Api\V1\Identity;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerIdentityVerificationRequest;
use App\Service\V1\identity\SubmitIdentityVerificationService;

class IdentityController extends Controller
{
    public function submit(WorkerIdentityVerificationRequest $request, SubmitIdentityVerificationService $identityService)
    {
        $worker = $request->user();
        $data = $identityService->execute($worker, $request);

        return response()->json([
            'message' => 'Documentos enviados para verificação com sucesso.',
            'data' => $data->resolve(),
        ]);

    }
}
