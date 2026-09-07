<?php

namespace App\Exceptions\Auth;

use App\Exceptions\DomainException;

class NewUserSetupRequiredException extends DomainException
{
    public function __construct(string $token)
    {
        parent::__construct(
            message: 'Cadastro necessário.',
            status: 409,
            extra: [
                'authenticated' => false,
                'registration_token' => $token,
            ],
        );
    }
}
