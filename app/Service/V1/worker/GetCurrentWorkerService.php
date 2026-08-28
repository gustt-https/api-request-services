<?php

namespace App\Service\V1\worker;

use App\Http\Resources\RequestResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GetCurrentWorkerService
{
    public function execute(User $worker): ?JsonResource
    {
        $currentService = $worker->application()
            ->with('request')
            ->whereIn('status', ['accepted', 'in_progress'])
            ->whereNull('cancelled_at')
            ->latest('request_id')
            ->first();

        if (!$currentService) {
            return null;
        }

        return new RequestResource($currentService->request);
    }
}
