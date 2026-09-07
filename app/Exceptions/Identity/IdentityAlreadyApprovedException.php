<?php

namespace App\Exceptions\Identity;

use App\Exceptions\DomainException;

class IdentityAlreadyApprovedException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Sua identidade já foi verificada.',
            status: 409,
        );
    }
}
