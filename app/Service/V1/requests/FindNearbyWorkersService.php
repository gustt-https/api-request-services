<?php

namespace App\Service\V1\requests;

use App\Models\Device;
use App\Models\RequestService;
use App\Models\User;
use App\Models\WorkerProfile;

class FindNearbyWorkersService
{

    public function find(RequestService $request, int $radius): array
    {
        $workersInRadius = WorkerProfile::query()
            ->available()
            ->withRadius($request->latitude, $request->longitude, $radius)
            ->notAppliedToRequest($request->id)
            ->pluck('user_id');

        $tokens = Device::query()
            ->whereIn('user_id', $workersInRadius)
            ->pluck('token')
            ->toArray();

        return $tokens;
    }
}
