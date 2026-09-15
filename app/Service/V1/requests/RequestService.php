<?php

namespace App\Service\V1\requests;

use App\DTOs\Payments\CreatePaymentData;
use App\DTOs\Payments\PaymentData;
use App\Exceptions\Requests\ActiveServiceAlreadyExists;
use App\Http\Resources\RequestResource;
use App\Jobs\NotifyClientRequestExpired;
use App\Jobs\NotifyWorkersOfNewRequest;
use App\Models\Request;
use App\Models\User;
use App\Service\V1\firebase\FirebaseService;
use App\Service\V1\Payments\PaymentService;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestService
{
    protected int $radius = 5;

    public function __construct(
        protected FirebaseService $firebase,
        protected GenerateSecurityCodeService $generateCode,
    ) {}

    public function makeRequest(User $user, array $payload): JsonResource
    {
        if (
            $user->hasActiveService()
        ) {
            throw new ActiveServiceAlreadyExists();
        }

        $request = $user->requests()->create($payload);

        NotifyWorkersOfNewRequest::dispatch($request);
        NotifyClientRequestExpired::dispatch($request)->delay(now()->addMinutes(10));
        $this->generateCode->execute($request);

        return new RequestResource($request->load(['securityCode']));
    }

