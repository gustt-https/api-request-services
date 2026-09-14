<?php

namespace App\Service\V1\Payments\Gateways\Asaas;

use App\DTOs\Payments\CreatePaymentData;
use App\Service\V1\Payments\Client\AsaasClient;
use App\Service\V1\Payments\Contracts\PaymentGatewayInterface;

class AsaasPaymentGateway implements PaymentGatewayInterface
{
    public function __construct(protected AsaasClient $client) {}

    public function createPayment(CreatePaymentData $data)
    {
        // WIP: implementar cobrança Asaas (Pix)
    }
}
