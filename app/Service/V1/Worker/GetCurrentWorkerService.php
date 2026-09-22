<?php

namespace App\Service\V1\Worker;

use App\Enums\RequestStatus;
use App\Http\Resources\RequestResource;
use App\Models\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GetCurrentWorkerService
{
    public function execute(User $worker): ?JsonResource
    {
        $currentService = Request::query()
            ->where('worker_id', $worker->id)
            ->whereIn('status', [
                RequestStatus::ACCEPTED,
                RequestStatus::IN_PROGRESS
            ])
            ->with(['user', 'medias'])
            ->latest('id')
            ->first();

        if (!$currentService) {
            return null;
        }

        return new RequestResource($currentService);
    }
}
