<?php

namespace App\DTOs\Payments;

class PaymentData
{
    public function __construct(
        public string $provider,
        public string $providerPaymentId,
        public int $amount,
        public string $status,
        public ?string $paidAt = null
    ) {}
}
