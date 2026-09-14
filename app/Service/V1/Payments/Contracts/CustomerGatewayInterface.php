<?php

namespace App\Service\V1\Payments\Contracts;

use App\DTOs\Customer\CreateCustomerData;

interface CustomerGatewayInterface
{
    public function createCustomer(CreateCustomerData $data);

    public function getCustomer();
}