<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class SecurityCodeNotFound extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Código de segurança indisponível.',
            status: 409,
        );
    }
}
