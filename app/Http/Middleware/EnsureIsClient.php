<?php

namespace App\Http\Middleware;

use App\Exceptions\Profile\ClientProfileNotFound;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsClient
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user->clientProfile) {
            throw new ClientProfileNotFound();
        }

        return $next($request);
    }
}
