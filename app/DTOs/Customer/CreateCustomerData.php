<?php

namespace App\DTOs\Customer;

class CreateCustomerData
{
    public function __construct(
        public string $name,
        public string $cpfCnpj,
        public string $email,
        public ?string $phone = null
    ) {}

}
