<?php

namespace App\DTOs\Customer;

class CustomerData
{
    public function __construct(
        public string $id,
        public string $name,
        public string $cpfCnpj,
        public string $email
    ) {}
}
