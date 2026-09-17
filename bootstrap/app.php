<?php

use App\Exceptions\DomainException;
use App\Http\Middleware\AsaasWebhookSignature;
use App\Http\Middleware\EnsureIsClient;
use App\Http\Middleware\EnsureIsWorker;
use App\Http\Middleware\RegistrationTokenMiddleware;
use App\Http\Middleware\RequestStartThrottle;
use App\Http\Middleware\SendCodeThrottle;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withEvents()
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'worker' => EnsureIsWorker::class,
            'client' => EnsureIsClient::class,
            'registration' => RegistrationTokenMiddleware::class,
            'throttle.start' => RequestStartThrottle::class,
            'throttle.send-code' => SendCodeThrottle::class,
            'asaas.webhook.signature' => AsaasWebhookSignature::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport([
            DomainException::class,
        ]);

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request, \Throwable $e) => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Recurso não encontrado.',
            ], 404);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Não autenticado.',
            ], 401);
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para realizar esta ação.',
            ], 403);
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Verifique os dados enviados.',
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->respond(function (Response $response) {
            if (! request()->is('api/*') || $response->getStatusCode() < 500 || config('app.debug')) {
                return $response;
            }

            return response()->json([
                'success' => false,
                'message' => 'Não foi possível concluir esta ação. Tente novamente.',
            ], $response->getStatusCode());
        });
    })->create();
