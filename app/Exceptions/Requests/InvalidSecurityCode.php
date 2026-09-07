<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class InvalidSecurityCode extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Código de segurança inválido.',
            status: 422,
        );
    }
}
