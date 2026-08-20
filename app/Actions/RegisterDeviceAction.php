<?php

namespace App\Actions;

use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class RegisterDeviceAction 
{
    public function handle(array $data)
    {
        $device = Device::updateOrCreate(
            ['device_id' => $data['device_id']],
            [
                'user_id' => Auth::id(),
                'token' => $data['token'],
                'plataform' => $data['plataform'],
                'is_active' => true,
                'last_used_at' => now(),
            ]
        );

        return $device;
    }
}