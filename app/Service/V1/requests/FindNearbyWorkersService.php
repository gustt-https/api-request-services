<?php

namespace App\Service\V1\requests;

use App\Models\User;
use App\Models\WorkerProfile;

class FindNearbyWorkersService
{

    public function execute(float $latitude, float $longitude, float $radius): array
    {
        $workersInRadius = WorkerProfile::query()
                                        ->available()
                                        ->withRadius($latitude, $longitude, $radius)
                                        ->with('user.devices')
                                        ->pluck('token')
                                        ->toArray();

        return $workersInRadius;
    }
}
