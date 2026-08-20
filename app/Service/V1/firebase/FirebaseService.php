<?php

namespace App\Service\V1\firebase;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    public function __construct(protected Messaging $messaging) {}

    public function sendPush(string $token)
    {
        $message = CloudMessage::new()->fromArray([
            'token' => $token,
            'notification' => [
                'title' => 'Há um novo serviço para você',
                'body' => 'Aproveite!'
            ]
        ]);

        $this->messaging->send($message);

        return true;
    }
}
