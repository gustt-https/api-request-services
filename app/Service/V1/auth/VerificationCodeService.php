<?php

namespace App\Service\V1\auth;

use App\Exceptions\InvalidEmailCode;
use App\Exceptions\NewUserException;
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

        // Ajustado: Cache::clear() apagava o cache inteiro; aqui só o código deste e-mail.
        Cache::forget('email-code:' . $email);

        $user = User::query()->where('email', $email)->first();

        // Incomplete signup: the first verify creates the user row, but name/CPF
        // were never finished. Keep issuing registration tokens until that happens.
        if (!$user || blank($user->name)) {
            if (!$user) {
                $user = User::create([
                    'email' => $email,
                    'password' => Str::random(64),
                    'email_verified_at' => now(),
                ]);
            }

            $registrationToken = $user
                ->createToken('registration', ['server:registration'], now()->addMinutes(15))
                ->plainTextToken;

            throw new NewUserException($registrationToken);
        }

        $token = $user->createToken('mobile', ['server:access'])->plainTextToken;
        return $token;
    }
}
