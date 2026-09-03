<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyCodeRequest;
use App\Service\V1\auth\VerificationCodeService;

class VerificationCodeController extends Controller
{
    public function __invoke(VerifyCodeRequest $request, VerificationCodeService $auth)
    {
        $token = $auth->verifyCode(
            $request->input('email'),
            $request->input('code'),
        );

        return response()->json([
            'success' => true,
            'message' => 'Autenticação realizada com successo',
            'action' => 'NAVIGATE_TO_DASHBOARD',
            'token' => $token,
        ]);
    }
}
