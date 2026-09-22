<?php

namespace App\Service\V1\Payments\Gateways\Asaas;

use App\DTOs\Payments\CreatePaymentData;
use App\DTOs\Payments\DeletePaymentData;
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
            'externalReference' => $data->externalReference,
            'billingType' => $data->billingType,
            'value' => $data->value,
            'dueDate' =>  $data->dueDate
        ]);

        if (!isset($payment['id'])) return null;

        return $this->toPaymentData($payment);
    }

    public function getPixQrCode(string $paymentId)
    {
        $qrCode = $this->client->get("payments/{$paymentId}/pixQrCode");

        if (!$qrCode || empty($qrCode['payload'])) {
            throw new PaymentCreationFailed();
        }
        return $qrCode['payload'];
    }

    public function cancelPayment(string $paymentId)
    {
        $data =  $this->client->delete("payments/{$paymentId}");

        return new DeletePaymentData(
            id: $data['id'],
            deleted: (bool) $data['deleted']
        );
    }

    public function getPayment(string $externalReference): ?PaymentData
    {
        $response = $this->client->get('/payments', [
            'externalReference' => $externalReference,
        ]);

        $item = $response['data'][0] ?? null;

        if (! $item) {
            return null;
        }

        return $this->toPaymentData($item);
    }

    private function toPaymentData(array $payment): PaymentData
    {
        return new PaymentData(
            provider: 'Asaas',
            providerPaymentId: (string) $payment['id'],
            amount: number_format((float) $payment['value'], 2, '.', ''),
            status: (string) $payment['status'],
        );
    }
}
