<?php

namespace App\Service\V1\Auth;

use App\Exceptions\Auth\InvalidEmailCode;
use App\Exceptions\Auth\NewUserSetupRequiredException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VerificationCodeService
{
    public const REGISTRATION_TOKEN_PREFIX = 'registration-token:';
    public const REGISTRATION_TOKEN_TTL = 300;

    public static function registrationTokenKey(string $token): string
    {
        return self::REGISTRATION_TOKEN_PREFIX . $token;
    }

    public function verifyCode(string $email, string $code): string
    {
        $codeSaved = Cache::get('email-code:' . $email);

        if (!$codeSaved) {
            throw new InvalidEmailCode();
        }

        $hash = is_array($codeSaved) ? ($codeSaved['code'] ?? null) : $codeSaved;

        if (!$hash || !Hash::check($code, $hash)) {
            $this->consumeFailedAttempt($email, $codeSaved);
            throw new InvalidEmailCode();
        }

        Cache::forget('email-code:' . $email);

        $user = User::query()->where('email', $email)->first();

        if (!$user) {
            $registrationToken = Str::random(64);
            Cache::put(
                self::registrationTokenKey($registrationToken),
                $email,
                self::REGISTRATION_TOKEN_TTL
            );

            throw new NewUserSetupRequiredException($registrationToken);
        }

        return $user->createToken('mobile', ['mobile-app'])->plainTextToken;
    }

    private function consumeFailedAttempt(string $email, mixed $codeSaved): void
    {
        $cacheKey = 'email-code:' . $email;

        if (! is_array($codeSaved)) {
            Cache::forget($cacheKey);
            return;
        }

        $attempts = (int) ($codeSaved['attempts'] ?? 1) - 1;

        if ($attempts <= 0) {
            Cache::forget($cacheKey);
            return;
        }

        $codeSaved['attempts'] = $attempts;
        Cache::put($cacheKey, $codeSaved, 900);
    }
}
