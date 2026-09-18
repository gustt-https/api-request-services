<?php

namespace App\Service\V1\requests;

use App\Http\Resources\RequestResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowRequests
{
    public function execute(User $client): JsonResource
    {
        $requests = $client->requests()
            ->with(['worker.workerProfile', 'securityCode', 'servicePackage'])
            ->latest('id')
            ->get();
        
        return RequestResource::collection($requests);
    }
}
