<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class FailedAcceptRequest extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Não foi possível aceitar esta solicitação.',
            status: 409,
        );
    }
}
