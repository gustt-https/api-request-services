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

        $cacheKey = "email-code:" . $email;
        $cacheData = ['code' => Hash::make($code), 'attempts' => 3];

        Cache::put($cacheKey, $cacheData, 900);

        Mail::to($email)->send(new VerificationCodeMail($code));
        return true;
    }
}
