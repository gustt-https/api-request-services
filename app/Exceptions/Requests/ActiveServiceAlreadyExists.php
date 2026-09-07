<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class ActiveServiceAlreadyExists extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Você já possui um serviço em andamento.',
            status: 409,
        );
    }
}
