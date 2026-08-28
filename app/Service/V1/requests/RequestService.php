<?php

namespace App\Service\V1\requests;

use App\Http\Resources\RequestResource;
use App\Jobs\NotifyWorkersOfNewRequest;
use App\Models\RequestService as ModelsRequestService;
use App\Models\User;
use App\Service\V1\firebase\FirebaseService;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestService
{

    public function __construct(
        protected FirebaseService $firebase,
        protected GenerateSecurityCodeService $generateCode,
    ) {}

    public function makeRequest(User $user, array $payload): JsonResource
    {
        $request = $user->requests()->create($payload);
        NotifyWorkersOfNewRequest::dispatch($request);

        $code =  $this->generateCode->execute($request);

        return new RequestResource($request)->additional(['code' => $code]);
    }
}
