<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Service\V1\auth\RegistrationService;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function clientRegister(RegistrationRequest $request, RegistrationService $user)
    {

        $cpf = $request->input('cpf');
        $name = $request->input('name');

        $authUser = Auth::user();
        $token = $user->register($authUser, $cpf, $name);

        return response()->json([
            'success' => true,
            'message' => 'Usuario criado com sucesso.',
            'action' => 'NAVIGATE_TO_DASHBOARD',
            'token' => $token
        ]);
    }

    public function workerRegister(RegistrationRequest $request, RegistrationService $user)
    {
        $cpf = $request->input('cpf');
        $name = $request->input('name');

        $authUser = Auth::user();
        $token = $user->register($authUser, $cpf, $name);

        return response()->json([
            'success' => true,
            'message' => 'Usuario criado com sucesso.',
            'action' => 'NAVIGATE_TO_DASHBOARD',
            'token' => $token
        ]);
    }
}
