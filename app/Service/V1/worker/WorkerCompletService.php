<?php

namespace App\Service\V1\worker;

use App\Http\Resources\WorkerServicesCompletedResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerCompletService
{
    public function execute(User $worker): ?JsonResource
    {
        $application = $worker->application()
            ->whereNotNull('completed_at')
            ->with('request')
            ->paginate();

        return WorkerServicesCompletedResource::collection($application);
    }
}