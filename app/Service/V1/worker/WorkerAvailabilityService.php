<?php

namespace App\Service\V1\worker;

use App\Http\Resources\WorkerAvailibilityResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerAvailabilityService
{
    public function enable(User $worker, string $latitude, string $longitude): void
    {
        $profile = $worker->workerProfile;

        $profile->latitude = $latitude;
        $profile->longitude = $longitude;
        $profile->available = true;
        $profile->last_location_at = now();
        $profile->save();
    }

    public function disabled(User $worker): void
    {
        $profile = $worker->workerProfile;
        $profile->available = false;
        $profile->save();
    }

    public function currentAvailability(User $worker): ?JsonResource
    {
        return new WorkerAvailibilityResource($worker->workerProfile);
    }
}
