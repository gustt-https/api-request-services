<?php

namespace App\Exceptions\Identity;

use App\Exceptions\DomainException;

class WorkerProfileNotFound extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Não foi possível enviar a verificação de identidade.',
            status: 403,
        );
    }
}
