<?php

namespace App\Service\V1\Payments\Gateways\Asaas;

use App\DTOs\Customer\CreateCustomerData;
use App\DTOs\Customer\CustomerData;
use App\Service\V1\Payments\Client\AsaasClient;
use App\Service\V1\Payments\Contracts\CustomerGatewayInterface;

class AsaasCustomerGateway implements CustomerGatewayInterface
{
    public function __construct(protected AsaasClient $client) {}

    public function createCustomer(CreateCustomerData $data): ?CustomerData
    {
        $payload = $this->client->post('/customers', [
            'name' => $data->name,
            'cpfCnpj' => $data->cpfCnpj,
            'email' => $data->email
        ]);

        if (!isset($payload['id'])) return null;

        return new CustomerData(
            $payload['id'],
            $payload['name'],
            $payload['cpfCnpj'],
            $payload['email']
        );
    }

    public function getCustomer() {}
}
