<?php

namespace App\Service\V1\requests;

use App\Jobs\NotifyWorkersOfNewRequest;
use App\Models\RequestService as ModelsRequestService;
use App\Models\User;
use App\Service\V1\firebase\FirebaseService;


class RequestService
{

    public function __construct(protected FirebaseService $firebase) {}

    public function makeRequest(User $user, array $payload): ModelsRequestService
    {
        $request = $user->requests()->create($payload);
        NotifyWorkersOfNewRequest::dispatch($request);

        return $request;
        
    }
}
