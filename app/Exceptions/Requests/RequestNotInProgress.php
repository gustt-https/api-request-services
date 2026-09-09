<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class RequestNotInProgress extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Só é possível finalizar um serviço em andamento.',
            status: 409,
        );
    }
}
