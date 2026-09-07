<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class SecurityCodeAlreadyUsed extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Este código de segurança já foi utilizado.',
            status: 409,
        );
    }
}
