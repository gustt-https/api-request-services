<?php

namespace App\Exceptions\Profile;

use App\Exceptions\DomainException;

class ClientProfileNotFound extends DomainException
{
    public function __construct(?string $message = null)
    {
        parent::__construct(
            message: $message ?? 'Perfil de cliente não encontrado.',
            status: 403,
        );
    }
}
