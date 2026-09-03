<?php

namespace App\Service\V1\auth;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class RegistrationClientService
{

    public function register(array $payload): string
    {
        $user = User::create(
            [
                'email' => $payload['email'],
                'name' => $payload['name'],
                'cpf' => $payload['cpf'],
                'password' => $payload['password'],
                'email_verified_at' => now()
            ]
        );

        $user->clientProfile()->create();

        $token = $user->createToken('mobile-app');

        return $token;

    }
}
