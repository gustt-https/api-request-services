<?php

namespace App\Exceptions\Identity;

use App\Exceptions\DomainException;

class IdentityVerificationPendingException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Sua verificação de identidade já foi enviada e está em análise.',
            status: 409,
        );
    }
}
