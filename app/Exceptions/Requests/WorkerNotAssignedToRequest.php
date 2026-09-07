<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class WorkerNotAssignedToRequest extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Você não está atribuído a este serviço.',
            status: 403,
        );
    }
}
