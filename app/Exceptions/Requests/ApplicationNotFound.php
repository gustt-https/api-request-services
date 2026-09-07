<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class ApplicationNotFound extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Não foi possível continuar este serviço.',
            status: 409,
        );
    }
}
