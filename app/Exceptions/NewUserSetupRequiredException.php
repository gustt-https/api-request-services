<?php

namespace App\Exceptions;

use Exception;

class NewUserSetupRequiredException extends Exception
{
    public function __construct(protected string $token)
    {
        parent::__construct('Cadastro necessário.');
    }

    public function render()
    {
        return response()->json([
            'authenticated' => false,
            'registration_token' => $this->token
        ]);
    }
}
