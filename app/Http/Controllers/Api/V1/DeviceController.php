<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\RegisterDeviceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterDeviceRequest;
use Illuminate\Http\Request;

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
}
