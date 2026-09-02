<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RegistrationTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $registrationToken = $request->bearerToken();

        if (!$registrationToken) {
            return response()->json([
                'message' => 'Token não fornecido ou invalido'
            ], 403);
        }

        $validToken = Cache::get('registration-token:' . $registrationToken);

        if (!$validToken) {

            return response()->json([
                'message' => 'Token invalido'
            ], 403);
        }

        $request->attributes->set('email', $validToken);
        
        return $next($request);
    }
}
