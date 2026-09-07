<?php

namespace App\Exceptions\Auth;

use App\Exceptions\DomainException;

class RegistrationTokenInvalidException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Não foi possível continuar o cadastro.',
            status: 403,
        );
    }
}
