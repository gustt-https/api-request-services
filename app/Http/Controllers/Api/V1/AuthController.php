<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\SendCodeRequest;
use App\Http\Requests\VerifyCodeRequest;
use App\Service\V1\auth\RegistrationService;
use App\Service\V1\auth\SendEmailCodeService;
use App\Service\V1\auth\VerificationCodeService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function send(SendCodeRequest $request, SendEmailCodeService $code)
    {
        $email = $request->input('email');
        $code->sendCode($email);

        return response()->json([
            'success' => true,
            'message' => 'Codigo de verificação enviado com sucesso'
        ]);
    }


    public function verify(VerifyCodeRequest $request, VerificationCodeService $auth)
    {

        $email = $request->input('email');
        $code  = $request->input('code');

        $token = $auth->verifyCode($email, $code);

        return response()->json([
            'success' => true,
            'message' => 'Autenticação realizada com successo',
            'action' => 'NAVIGATE_TO_DASHBOARD',
            'token' => $token
        ]);
    }

    public function register(RegistrationRequest $request, RegistrationService $user)
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
