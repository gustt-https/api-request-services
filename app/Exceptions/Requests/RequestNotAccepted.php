<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class RequestNotAccepted extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Este serviço ainda não foi aceito.',
            status: 409,
        );
    }
}
