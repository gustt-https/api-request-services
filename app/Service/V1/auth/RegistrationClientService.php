<?php

namespace App\Service\V1\auth;

use App\Models\User;

class RegistrationClientService
{

    public function register(User $user, string $cpf, string $name): string
    {
        $user->cpf = $cpf;
        $user->name = $name;
        $user->email_verified_at = now();
        $user->save();

        $user->clientProfile()->create();

        // Ajustado: inclui server:access — as rotas autenticadas exigem essa ability, não só server:client.
        $token = $user->createToken('client', ['server:access', 'server:client'])->plainTextToken;
        return $token;
    }

}
