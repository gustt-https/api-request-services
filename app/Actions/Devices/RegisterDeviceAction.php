<?php

namespace App\Actions\Devices;

use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class RegisterDeviceAction 
{
    public function handle(array $data)
    {
        $device = Device::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'device_id' => $data['device_id'],
                'token' => $data['token'],
                'plataform' => $data['plataform'],
                'is_active' => true,
                'last_used_at' => now(),
            ]
        );

        return $device;
    }
}