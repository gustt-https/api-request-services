<?php

namespace App\Service\V1\Requests;

use App\Models\User;

class FindWorkerOfRequest
{
    public function find(int $workerId): User
    {
        return User::query()
            ->whereKey($workerId)
            ->firstOrFail();
    }
}
