<?php

namespace App\Service\V1\requests;

use App\Enums\RequestStatus;
use App\Http\Resources\RequestResource;
use App\Models\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrentRequest
{
    public function execute(User $user): JsonResource|null
    {
        $currentRequest = Request::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                RequestStatus::IN_PROGRESS,
                RequestStatus::ACCEPTED,
                RequestStatus::SEARCHING
            ])
            ->with('worker')
            ->latest('id')
            ->first();

        if (!$currentRequest) {
            return null;
        }

        return  new RequestResource($currentRequest);
    }
}
