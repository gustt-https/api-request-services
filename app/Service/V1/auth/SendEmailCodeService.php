<?php

namespace App\Service\V1\auth;

use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SendEmailCodeService
{


    public function sendCode(string $email)
    {
        $code = random_int(100000, 999999);

        Cache::put('email-code:' . $email, Hash::make($code), 900);

        Mail::to($email)->send(new VerificationCodeMail($code));
        return true;
    }
}
