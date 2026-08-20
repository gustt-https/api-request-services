<?php

namespace App\Service\V1\requests;

use App\Jobs\NotifyWorkersOfNewRequest;
use App\Models\Device;
use App\Models\User;
use App\Service\V1\firebase\FirebaseService;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class RequestService
{

    public function __construct(protected FirebaseService $firebase) {}

    public function makeRequest(User $user, array $payload)
    {
        $request = $user->requests()->create($payload);
        $device = Device::query()->where('user_id', 1)->first();
        $token = $device->token;
        NotifyWorkersOfNewRequest::dispatch($token);



        return $request;
    }
}
