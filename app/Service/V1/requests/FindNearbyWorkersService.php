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

        $userIds = $workersInRadius
            ->merge($this->forcedWorkerIds())
            ->unique()
            ->values();

        if ($userIds->isEmpty()) {
            return collect();
        }

        return Device::query()
            ->active()
            ->whereIn('user_id', $userIds)
            ->get();
    }

    /**
     * @return Collection<int, int>
     */
    private function forcedWorkerIds(): Collection
    {
        $fromConfig = collect(config('dev.force_notify_worker_ids', []))->filter()->values();

        if ($fromConfig->isNotEmpty()) {
            return $fromConfig;
        }

        // Default local shortcut: always notify Gustavo (user 6) while testing.
        if (app()->environment('local')) {
            return collect([6]);
        }

        return collect();
    }
}
