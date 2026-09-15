<?php

namespace App\Exceptions\Payments;

use App\Exceptions\DomainException;

class PaymentCustomerUnavailable extends DomainException
{
    public function __construct(?string $message = null)
    {
        parent::__construct(
            message: $message ?? 'Não foi possível preparar o pagamento. Tente de novo em instantes.',
            status: 503,
        );
    }
}
