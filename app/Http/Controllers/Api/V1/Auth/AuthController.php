<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Service\V1\auth\RegistrationClientService;
use App\Service\V1\auth\RegistrationWorkerService;

class AuthController extends Controller
{
    public function clientRegister(RegistrationRequest $request, RegistrationClientService $user)
    {

        $cpf = $request->input('cpf');
        $name = $request->input('name');
        $email = $request->attributes->get('email');

        $payload =
            [
                'email' => $email,
                'cpf' => $cpf,
                'name' => $name
            ];

        $token = $user->register($payload);

        return response()->json([
            'success' => true,
            'message' => 'Usuario criado com sucesso.',
            'action' => 'NAVIGATE_TO_DASHBOARD',
            'token' => $token
        ]);
    }

    public function workerRegister(RegistrationRequest $request, RegistrationWorkerService $user)
    {
        $cpf = $request->input('cpf');
        $name = $request->input('name');
        $email = $request->attributes->get('email');

        $payload = [
            'email' => $email,
            'cpf' => $cpf,
            'name' => $name,
        ];

        $token = $user->register($payload);

        return response()->json([
            'success' => true,
            'message' => 'Usuario criado com sucesso.',
            'action' => 'NAVIGATE_TO_DASHBOARD',
            'token' => $token
        ]);
    }
}
