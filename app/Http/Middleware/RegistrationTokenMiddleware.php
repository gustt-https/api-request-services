<?php

namespace App\Http\Middleware;

use App\Service\V1\auth\VerificationCodeService;
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

        $email = Cache::get(VerificationCodeService::registrationTokenKey($registrationToken));

        if (!$email) {
            return response()->json([
                'message' => 'Token invalido'
            ], 403);
        }

        $request->attributes->set('email', $email);

        $response = $next($request);

        if ($response->getStatusCode() < 400) {
            Cache::forget(VerificationCodeService::registrationTokenKey($registrationToken));
        }

        return $response;
    }
}
