<?php

namespace App\Http\Middleware;

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
            return response()->json([
                'success' => false,
                'message' => 'Você não tem o perfil necessario'
            ], 403);
        }

        return $next($request);
    }
}
