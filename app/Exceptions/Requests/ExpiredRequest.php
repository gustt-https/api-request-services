<?php

namespace App\Exceptions\Requests;

use App\Exceptions\DomainException;

class ExpiredRequest extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Esta solicitação expirou. Nenhum profissional aceitou a tempo.',
            status: 409,
        );
    }
}
