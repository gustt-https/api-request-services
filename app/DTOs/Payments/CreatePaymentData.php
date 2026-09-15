<?php

namespace App\DTOs\Payments;

class CreatePaymentData
{
    public function __construct(
        public string $customer,
        public string $billingType,
        public string $value,
        public string $dueDate
    ) {}
}
