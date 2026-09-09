<?php

namespace App\Actions;

use App\Http\Resources\MeResource;
use App\Models\User;
use App\Service\V1\worker\WorkerStatsService;

class Me
{
    public function __construct(
        private WorkerStatsService $stats,
    ) {}

    public function handle(User $user): MeResource
    {
        $me = $user->load(['clientProfile', 'workerProfile.identityVerification']);

        $stats = $me->workerProfile ? $this->stats->forWorker($me) : null;

        return new MeResource($me, $stats);
    }
}
