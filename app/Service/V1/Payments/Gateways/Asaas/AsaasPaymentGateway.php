<?php

namespace App\Service\V1\Payments\Gateways\Asaas;

use App\DTOs\Payments\CreatePaymentData;
use App\DTOs\Payments\PaymentData;
use App\Exceptions\Payments\PaymentCreationFailed;
use App\Service\V1\Payments\Client\AsaasClient;
use App\Service\V1\Payments\Contracts\PaymentGatewayInterface;

class AsaasPaymentGateway implements PaymentGatewayInterface
{
    public function __construct(protected AsaasClient $client) {}

    public function createPayment(CreatePaymentData $data): ?PaymentData
    {
        $payment = $this->client->post('/lean/payments', [
            'customer' => $data->customer,
            'billingType' => $data->billingType,
            'value' => $data->value,
            'dueDate' =>  $data->dueDate
        ]);

        if (!isset($payment['id'])) return null;

        return new PaymentData(
            provider: 'Asaas',
            providerPaymentId: (string) $payment['id'],
            amount: (float) number_format($payment['value'], 2, '.', ''),
            status: (string) $payment['status'],
        );
    }

    public function getPixQrCode(string $paymentId)
    {
        $qrCode = $this->client->get("payments/{$paymentId}/pixQrCode");

        if (!$qrCode || empty($qrCode['payload'])) {
            throw new PaymentCreationFailed();
        }
        return $qrCode['payload'];
    }
}
