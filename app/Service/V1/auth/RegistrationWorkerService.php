<?php

namespace App\Service\V1\auth;

use App\Models\User;
use Illuminate\Support\Str;

class RegistrationWorkerService
{
    public function register(array $payload): string
    {
        $user = User::query()->firstOrCreate(
            ['email' => $payload['email']],
            [
                'name' => $payload['name'],
                'cpf' => $payload['cpf'],
                'password' => Str::random(32),
            ]
        );

        $user->name = $payload['name'];
        $user->cpf = $payload['cpf'];
        $user->email_verified_at = now();
        $user->save();

        if (!$user->workerProfile) {
            $user->workerProfile()->create();
        }

        return $user->createToken('mobile-app')->plainTextToken;
    }
}
