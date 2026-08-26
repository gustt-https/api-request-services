<?php

namespace App\Exceptions;

use Exception;

class FailedCancelRequestService extends Exception
{
    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => 'Não foi possível cancelar esta solicitação.',
        ], 409);
    }
}
