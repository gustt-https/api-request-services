<?php

namespace App\Service\V1\worker;

use App\Http\Resources\RequestResource;
use App\Models\User;

class GetCurrentWorkerService
{
    public function execute(User $worker)
    {
        $currentService = $worker->application()
            ->with('request')
            ->whereIn('status', ['accepted', 'in_progress'])
            ->whereNull('cancelled_at')
            ->latest('request_id')
            ->first()->request;

        return new RequestResource($currentService);
    }
}
