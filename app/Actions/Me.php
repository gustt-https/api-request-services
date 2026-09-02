<?php

namespace App\Actions;

use App\Http\Resources\MeResource;
use App\Models\User;

class Me
{
    public function handle(User $user)
    {
        $me = $user->load(['clientProfile', 'workerProfile.identityVerification']);
        return new MeResource($me);
    }
}
