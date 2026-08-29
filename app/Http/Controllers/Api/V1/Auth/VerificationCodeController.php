<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Exceptions\NewUserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyCodeRequest;
use App\Service\V1\auth\VerificationCodeService;

class VerificationCodeController extends Controller
{
    public function __invoke(VerifyCodeRequest $request, VerificationCodeService $auth)
    {
        $email = $request->input('email');
        $code = $request->input('code');

        try {
            $token = $auth->verifyCode($email, $code);
        } catch (NewUserException $exception) {
            // Return explicitly so clients always get action/token (not a 500 debug payload).
            return $exception->render();
        }

        return response()->json([
            'success' => true,
            'message' => 'Autenticação realizada com successo',
            'action' => 'NAVIGATE_TO_DASHBOARD',
            'token' => $token,
        ]);
    }
}
