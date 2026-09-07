<?php

namespace App\Service\V1\requests;

use App\Models\Request;
use App\Models\User;

class FindClientOfRequest
{
    public function find(Request $request): User
    {
        $client = User::query()
            ->whereKey($request->user_id)
            ->firstOrFail();

        return $client;
    }
}
