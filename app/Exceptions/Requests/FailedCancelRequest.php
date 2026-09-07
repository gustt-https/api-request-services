<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class FailedCancelRequest extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Não foi possível cancelar esta solicitação.',
            status: 409,
        );
    }
}
