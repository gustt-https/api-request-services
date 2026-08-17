<?php

namespace App\Exceptions;

use Exception;


class NewUserException extends Exception
{
    public function __construct(protected string $token)
    {
        parent::__construct('Cadastro necessário.');
    }

    public function render()
    {
        return response()->json([
            'success' => true,
            'action' => 'NEW_USER',
            'message' => $this->getMessage(),
            'token' => $this->token
        ]);
    }
}
