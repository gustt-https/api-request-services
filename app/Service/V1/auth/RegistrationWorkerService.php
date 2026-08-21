<?php

namespace App\Service\V1\auth;

use App\Models\User;

class RegistrationService
{

    public function register(User $user, string $cpf, string $name): string
    {
        $user->cpf = $cpf;
        $user->name = $name;
        $user->email_verified_at = now();
        $user->save();

        $user->workerProfile()->create();

        $token = $user->createToken('worker', ['server:worker'])->plainTextToken;
        return $token;
    }
}
