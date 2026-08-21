<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendCodeRequest;
use App\Service\V1\auth\SendEmailCodeService;

class SendVerificationCodeController extends Controller
{
    public function __invoke(SendCodeRequest $request, SendEmailCodeService $code)
    {
        $email = $request->input('email');
        $code->sendCode($email);

        return response()->json([
            'success' => true,
            'message' => 'Codigo de verificação enviado com sucesso'
        ]);
    }
}
