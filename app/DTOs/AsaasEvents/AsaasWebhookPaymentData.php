<?php

namespace App\DTOs\AsaasEvents;

class AsaasWebhookPaymentData
{
    public function __construct(
        public string $object,
        public string $id
    ) {}
}
