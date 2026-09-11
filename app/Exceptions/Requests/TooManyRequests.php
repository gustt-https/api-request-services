<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class TooManyRequests extends DomainException
{
    public function __construct(?string $message = null, ?array $extra = [])
    {
        parent::__construct(
            message: $message ?? 'Muitas tentativas. Aguarde um momento e tente novamente.',
            status: 429,
            extra: $extra
        );
    }
}
