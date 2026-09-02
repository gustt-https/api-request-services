<?php

namespace App\Service\V1\auth;

use App\Exceptions\InvalidEmailCode;
use App\Exceptions\NewUserException;
use App\Exceptions\NewUserSetupRequiredException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VerificationCodeService
{

    public function verifyCode(string $email, string $code): string
    {
        $codeSaved = Cache::get('email-code:' . $email);

        if (!$codeSaved) {
            throw new InvalidEmailCode();
        }

        if (!Hash::check($code, $codeSaved)) {
            throw new InvalidEmailCode();
        }
        $user = User::query()->where('email', $email)->first();

        if (
            !$user
        ) {

            $registrationToken = Str::random(64);
            Cache::put('registration-token' . $registrationToken, '', 300);

            throw new NewUserSetupRequiredException($registrationToken);
        }

        $token = $user->createToken('mobile', ['mobile-app'])->plainTextToken;
        return $token;
    }
}
