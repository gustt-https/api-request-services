<?php

namespace App\Exceptions\Auth;

use App\Exceptions\DomainException;

class InvalidEmailCode extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Código inválido ou expirado.',
            status: 422,
        );
    }
}
