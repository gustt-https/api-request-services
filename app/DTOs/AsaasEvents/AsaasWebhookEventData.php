<?php

namespace App\DTOs\AsaasEvents;

class AsaasWebhookEventData
{
    public function __construct(
        public string $id,
        public string $event,
        public string $dateCreated,
        public AsaasWebhookPaymentData $payment
    ) {}
}
