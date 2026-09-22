<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Devices\DisableDeviceAction;
use App\Actions\Devices\RegisterDeviceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DisableDeviceRequest;
use App\Http\Requests\RegisterDeviceRequest;

class DeviceController extends Controller
{
    public function register(RegisterDeviceRequest $request, RegisterDeviceAction $registerDevice)
    {
        $payload = $request->validated();
        $registerDevice->handle($payload);

        return response()->json([
            'success' => true,
            'message' => 'Dispositivo cadastrado com successo'
        ]);
    }

    public function disabled(DisableDeviceRequest $request, DisableDeviceAction $disableDevice)
    {
        $payload = $request->validated();
        $disableDevice->handle($payload);

        return response()->json([
            'success' => true,
            'message' => 'Dispositivo desativado com sucesso'
        ]);
    }
}
