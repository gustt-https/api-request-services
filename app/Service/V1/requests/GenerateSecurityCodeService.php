<?php

namespace App\Service\V1\requests;

use App\Models\Request;

class GenerateSecurityCodeService
{
    public function execute(Request $request): string
    {
        $code = (string) random_int(100000, 999999);

        $request->securityCode()->create([
            'code' => $code,
        ]);

        return $code;
    }
}
