<?php

namespace App\Exceptions\Payments;

use App\Exceptions\DomainException;

class PaymentCreationFailed extends DomainException
{
    public function __construct(?string $message = null)
    {
        parent::__construct(
            message: $message ?? 'Não foi possível gerar o PIX. Tente de novo em instantes.',
            status: 503,
        );
    }
}
