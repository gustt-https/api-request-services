<?php

namespace App\Service\V1\Payments;

use App\DTOs\Customer\CreateCustomerData;

use App\Models\User;
use App\Service\V1\Payments\Contracts\CustomerGatewayInterface;

class CustomerService
{
    public function __construct(private CustomerGatewayInterface $gateway) {}

    public function create(User $user): ?string
    {
        $profile = $user->clientProfile;

        if ($profile->customer_id) {
            return $profile->customer_id;
        }

        $data = new CreateCustomerData(
            $user->name,
            $user->cpf,
            $user->email
        );

        $customer = $this->gateway->createCustomer($data);

        if (!$customer) return null;

        $profile->customer_id = $customer->id;
        $profile->save();

        return $customer->id;
    }
}
