<?php

namespace App\Service\V1\Payments\Contracts;

use App\DTOs\Payments\CreatePaymentData;
use App\Models\Request;

interface PaymentGatewayInterface
{

    public function createPayment(CreatePaymentData $data);

    public function getPixQrCode(string $providerPaymentId);
}