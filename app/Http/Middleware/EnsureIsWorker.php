<?php

namespace App\Http\Middleware;

use App\Exceptions\Profile\WorkerProfileNotFound;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsWorker
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user->workerProfile) {
            throw new WorkerProfileNotFound();
        }

        return $next($request);
    }
}
