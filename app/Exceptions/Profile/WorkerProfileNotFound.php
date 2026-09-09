<?php

namespace App\Exceptions\Profile;

use App\Exceptions\DomainException;

class WorkerProfileNotFound extends DomainException
{
    public function __construct(?string $message = null)
    {
        parent::__construct(
            message: $message ?? 'Perfil de profissional não encontrado.',
            status: 403,
        );
    }
}
