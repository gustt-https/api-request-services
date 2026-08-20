<?php

namespace App\Service\V1\firebase;

use App\Http\Resources\NotifyWorkersRequestResource;
use App\Models\RequestService;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;


class FirebaseService
{
    public function __construct(protected Messaging $messaging) {}

    public function sendNewRequestPush(array $tokens, RequestService $request)
    {

        $message = CloudMessage::new()->fromArray([
            'notification' => [
                'title' => 'Há um novo serviço para você',
                'body' => 'Aproveite!'
            ],
            'data' => new NotifyWorkersRequestResource($request)
        ]);

        $this->messaging->sendMulticast($message, $tokens);

        return true;
    }
}
