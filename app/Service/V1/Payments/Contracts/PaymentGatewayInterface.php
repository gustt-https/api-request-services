<?php

namespace App\Service\V1\Payments\Contracts;

use App\DTOs\Payments\CreatePaymentData;

interface PaymentGatewayInterface
{

    public function createPayment(CreatePaymentData $data);
}