<?php

namespace App\Http\Middleware;

use App\Exceptions\Auth\RegistrationTokenInvalidException;
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
            throw new RegistrationTokenInvalidException();
        }

        $email = Cache::get(VerificationCodeService::registrationTokenKey($registrationToken));

        if (!$email) {
            throw new RegistrationTokenInvalidException();
        }

        $request->attributes->set('email', $email);

        $response = $next($request);

        if ($response->getStatusCode() < 400) {
            Cache::forget(VerificationCodeService::registrationTokenKey($registrationToken));
        }

        return $response;
    }
}
