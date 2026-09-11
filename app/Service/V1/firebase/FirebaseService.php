<?php

namespace App\Service\V1\firebase;

use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Cloud;
use Illuminate\Support\Collection;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;


class FirebaseService
{
    public function __construct(protected Messaging $messaging) {}

    public function sendNewRequestPush(Collection $devices, array $data): bool
    {
        $tokens = $devices
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return false;
        }

        $message = CloudMessage::new()->fromArray([
            'notification' => [
                'body' => 'Novo pedido disponível na sua região'
            ],
            'data' => $data
        ]);

        $this->messaging->sendMulticast($message, $tokens);

        return true;
    }

    public function notifyClientWorkerAccepted(Collection $devices, array $data)
    {

        $tokens = $devices
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return false;
        }


        $message = CloudMessage::new()->fromArray([
            'notification' => [
                'body' => 'A sua solicitação foi aceita.'
            ],
            'data' => $data
        ]);

        $this->messaging->sendMulticast($message, $tokens);
    }

    public function notifyClientServiceStarted(Collection $devices, array $data)
    {
        $tokens = $devices
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return false;
        }

        $message = CloudMessage::new()->fromArray([
            'notification' => [
                'body' => 'O serviço foi iniciado.'
            ],
            'data' => $data
        ]);

        $this->messaging->sendMulticast($message, $tokens);
    }

    public function notifyClientServiceCompleted(Collection $devices, array $data)
    {
        $tokens = $devices
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return false;
        }

        $message = CloudMessage::new()->fromArray([
            'notification' => [
                'body' => 'O serviço foi encerrado.'
            ],
            'data' => $data
        ]);

        $this->messaging->sendMulticast($message, $tokens);
    }

    public function notifyClientWorkerCancelled(Collection $devices, array $data)
    {
        $tokens = $devices
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return false;
        }

        $message = CloudMessage::new()->fromArray([
            'notification' => [
                'body' => 'O profissional cancelou. Estamos buscando outro profissional.'
            ],
            'data' => $data
        ]);

        $this->messaging->sendMulticast($message, $tokens);
    }

    public function notifyWorkerClientCancelled(Collection $devices, array $data)
    {
        $tokens = $devices
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return false;
        }

        $message = CloudMessage::new()->fromArray([
            'notification' => [
                'body' => 'O cliente cancelou a solicitação.'
            ],
            'data' => $data
        ]);

        $this->messaging->sendMulticast($message, $tokens);
    }

    public function notifyClientRequestExpired(Collection $devices, array $data)
    {
        $tokens = $devices
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return false;
        }

        $message = CloudMessage::new()->fromArray([
            'notification' => [
                'body' => 'A solicitação expirou'
            ],
            'data' => $data
        ]);

        $this->messaging->sendMulticast($message, $tokens);
    }
}
