<?php

namespace App\Service\V1\worker;

use App\Http\Resources\RequestResource;
use App\Models\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GetCurrentWorkerService
{
    public function execute(User $worker): ?JsonResource
    {
        // Status lives on requests; lifecycle dates live on request_applications.
        $currentService = Request::query()
            ->where('worker_id', $worker->id)
            ->whereIn('status', ['accepted', 'in_progress'])
            ->latest('id')
            ->first();

        if (!$currentService) {
            return null;
        }

        return new RequestResource($currentService);
    }
}
