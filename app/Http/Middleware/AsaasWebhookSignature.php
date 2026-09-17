<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AsaasWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->header('asaas-access-token');
        $expected = (string) config('asaas.webhook_token');

        if (! is_string($token) || $token === '') {
            return response()->json([
                'message' => 'Token não fornecido'
            ], 401);
        }

        if ($expected === '' || ! hash_equals($expected, $token)) {
            return response()->json([
                'message' => 'Token invalido'
            ], 401);
        }


        return $next($request);
    }
}
