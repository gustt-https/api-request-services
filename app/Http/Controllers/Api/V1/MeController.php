<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Me;
use App\Http\Controllers\Controller;
use App\Http\Resources\MeResource;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function __invoke(Me $me)
    {
        $user = Auth::user();
        $data = $me->handle($user);

        return response()->json([
            'data' => $data
        ]);

        
    }
}
