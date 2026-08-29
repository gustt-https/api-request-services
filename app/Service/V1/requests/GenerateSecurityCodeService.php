<?php

namespace App\Service\V1\requests;

use App\Models\Request;
use Illuminate\Support\Facades\Hash;

class GenerateSecurityCodeService
{

    public function execute(Request $request): int
    {
        $code = random_int(100000, 999999);

        $request->securityCode()->create([
            'code' => Hash::make($code),
        ]);

        return $code;
    }
}
