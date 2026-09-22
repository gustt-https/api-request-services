<?php

namespace App\Actions\Devices;

use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class DisableDeviceAction
{
    public function handle(array $data): void
    {
        $device = Device::query()
            ->where('device_id', $data['device_id'])
            ->where('user_id', Auth::id())
            ->first();

        if (! $device) {
            return;
        }

        $device->is_active = false;
        $device->save();
    }
}
