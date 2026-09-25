<?php

namespace App\Service\V1\Worker;

use App\Enums\RequestStatus;
use App\Exceptions\Identity\IdentityIsNotVerified;
use App\Http\Resources\WorkerAvailibilityResource;
use App\Models\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerAvailabilityService
{

    public function __construct(protected GetCurrentWorkerService $currentService) {}

    public function enable(User $worker, string $latitude, string $longitude): void
    {

        if (! $worker->identityIsVerified()) {
            throw new IdentityIsNotVerified($worker->identityVerification?->status);
        }

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

    public function updateLocation(User $worker, $latitude, string $longitude)
    {
        // Accepted jobs are keyed by worker_id — User::requests() is the client side (user_id).
        $currentService = Request::query()
            ->where('worker_id', $worker->id)
            ->where('status', RequestStatus::ACCEPTED)
            ->first();

        if (! $currentService) {
            return null;
        }

        $profile = $worker->workerProfile;
        $profile->latitude = $latitude;
        $profile->longitude = $longitude;
        $profile->last_location_at = now();
        $profile->save();
    }
}
