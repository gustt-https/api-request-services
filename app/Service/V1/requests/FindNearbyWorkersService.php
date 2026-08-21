<?php

namespace App\Service\V1\requests;

use App\Models\Device;
use App\Models\User;
use App\Models\WorkerProfile;

class FindNearbyWorkersService
{

    public function find(float $latitude, float $longitude, float $radius): array
    {
        $workersInRadius = WorkerProfile::query()
            ->available()
            ->withRadius($latitude, $longitude, $radius)
            ->pluck('user_id');

        $tokens = Device::query()
            ->whereIn('user_id', $workersInRadius)
            ->pluck('token')
            ->toArray();

        return $tokens;
    }
}
