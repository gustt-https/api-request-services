<?php

namespace App\Service\V1\requests;

use App\Models\Device;
use App\Models\Request;
use App\Models\WorkerProfile;
use Illuminate\Support\Collection;

class FindNearbyWorkersService
{

    public function find(Request $request, int $radius): Collection
    {
        $workersInRadius = WorkerProfile::query()
            ->available()
            ->withRadius($request->latitude, $request->longitude, $radius)
            ->notAppliedToRequest($request->id)
            ->pluck('user_id');

        $devices = Device::query()
            ->whereIn('user_id', $workersInRadius)
            ->get();

        return $devices;
    }
}
