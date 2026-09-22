<?php

namespace App\Service\V1\Payments\Contracts;

use App\DTOs\Payments\CreatePaymentData;
use App\DTOs\Payments\PaymentData;

interface PaymentGatewayInterface
{

    public function createPayment(CreatePaymentData $data);

    public function getPixQrCode(string $providerPaymentId);

    public function cancelPayment(string $providerPaymentId);

    public function getPayment(string $externalReference): ?PaymentData;
}
