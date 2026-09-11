<?php

namespace App\Http\Middleware;

use App\Exceptions\Requests\TooManyRequests;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SendCodeThrottle
{
    const KEY = 'send:';
    const LIMIT = 1;
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local')) {
            return $next($request);
        }
        // Implementar validação do email..
        if (!$email = $request->input('email')) {
            return $next($request);
        }

        $key = self::KEY . $email . ':' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, self::LIMIT)) {
            $retryIn = RateLimiter::availableIn($key);
            throw new TooManyRequests('Aguarde antes de solicitar um novo código', ['retry_after' => $retryIn]);
        }

        RateLimiter::hit($key);
        return $next($request);
    }
}
