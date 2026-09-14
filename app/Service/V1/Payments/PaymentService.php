<?php

namespace App\Service\V1\Payments;

use App\DTOs\Payments\CreatePaymentData;
use App\Service\V1\Payments\Contracts\PaymentGatewayInterface;

class PaymentService
{
    public function __construct(private PaymentGatewayInterface $gateway) {}

    public function create(CreatePaymentData $data)
    {
        // WIP: orquestrar payment local + gateway
        return $this->gateway->createPayment($data);
    }
}
