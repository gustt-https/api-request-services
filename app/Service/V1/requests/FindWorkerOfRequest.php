<?php

namespace App\Service\V1\requests;

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
