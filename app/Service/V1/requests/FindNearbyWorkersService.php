<?php

namespace App\Service\V1\requests;

use App\Models\User;

class FindNearbyWorkersService
{
    public function execute(float $latitude, float $longitude, float $radius)
    {
        $users = User::available()->get();
        
    }
}