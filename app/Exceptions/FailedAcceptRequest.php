<?php

namespace App\Exceptions;

use Exception;

class FailedAcceptRequest extends Exception
{
    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => 'Não foi possível aceitar esta solicitação.',
        ], 409);
    }
}
