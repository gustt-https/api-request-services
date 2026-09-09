<?php

namespace App\Http\Middleware;

use App\Exceptions\Requests\TooManyRequests;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RequestStartThrottle
{
    const KEY = 'start:';

    const LIMIT = 5;
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $requestModel = $request->route('requestService');
        $key = self::KEY . $user->id . ':' . $requestModel->id;

        if (
            RateLimiter::tooManyAttempts(
                $key,
                self::LIMIT
            )
        ) {
            throw new TooManyRequests();
        }

        RateLimiter::hit($key, 60);
        $response = $next($request);

        if (
            $response->getStatusCode() === 200
        ) {
            RateLimiter::clear($key);
        }

        return $response;
    }
}
