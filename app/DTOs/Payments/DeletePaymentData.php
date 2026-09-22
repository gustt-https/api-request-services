<?php

namespace App\DTOs\Payments;

class DeletePaymentData
{
    public function __construct(
        public string $id,
        public bool $deleted
    ) {}
}
