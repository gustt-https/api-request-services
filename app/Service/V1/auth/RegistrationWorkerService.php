<?php

namespace App\Service\V1\auth;

use App\Models\User;

class RegistrationWorkerService
{

    public function register(User $user, string $cpf, string $name): string
    {
        $user->cpf = $cpf;
        $user->name = $name;
        $user->email_verified_at = now();
        $user->save();

        $user->workerProfile()->create();

        // Ajustado: inclui server:access — as rotas autenticadas exigem essa ability, não só server:worker.
        $token = $user->createToken('worker', ['server:access', 'server:worker'])->plainTextToken;
        return $token;
    }
}
