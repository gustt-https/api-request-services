<?php

namespace App\Service\V1\firebase;

use Illuminate\Support\Collection;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseService
{
    public function __construct(protected Messaging $messaging) {}

    /** FCM data payloads must be string values only. */
    private function stringData(array $data): array
    {
        return array_map(static fn ($value) => $value === null ? '' : (string) $value, $data);
    }

    /**
     * @param  array{title: string, body: string}  $notification
     */
    private function sendToDevices(Collection $devices, array $notification, array $data): bool
    {
        $tokens = $devices->pluck('token')->filter()->values()->all();

        if ($tokens === []) {
            return false;
        }

        $message = CloudMessage::new()->fromArray([
            'notification' => $notification,
            'data' => $this->stringData($data),
        ]);

        $this->messaging->sendMulticast($message, $tokens);

        return true;
    }

    public function sendNewRequestPush(Collection $devices, array $data): bool
    {
        return $this->sendToDevices($devices, [
            'title' => 'Novo pedido perto de você',
            'body' => 'Uma limpeza está disponível na sua região. Abra para aceitar.',
        ], $data);
    }

    public function notifyClientWorkerAccepted(Collection $devices, array $data): bool
    {
        return $this->sendToDevices($devices, [
            'title' => 'Profissional a caminho',
            'body' => 'Seu pedido foi aceito. Mostre o código de início quando chegar.',
        ], $data);
    }

    public function notifyClientServiceStarted(Collection $devices, array $data): bool
    {
        return $this->sendToDevices($devices, [
            'title' => 'Limpeza em andamento',
            'body' => 'O profissional iniciou o serviço no seu endereço.',
        ], $data);
    }

    public function notifyClientServiceCompleted(Collection $devices, array $data): bool
    {
        return $this->sendToDevices($devices, [
            'title' => 'Serviço concluído',
            'body' => 'A limpeza foi finalizada. Obrigado — até a próxima.',
        ], $data);
    }

    public function notifyClientWorkerCancelled(Collection $devices, array $data): bool
    {
        return $this->sendToDevices($devices, [
            'title' => 'Buscando outro profissional',
            'body' => 'Quem tinha aceito cancelou. Continuamos procurando perto de você.',
        ], $data);
    }

    public function notifyWorkerClientCancelled(Collection $devices, array $data): bool
    {
        return $this->sendToDevices($devices, [
            'title' => 'Pedido cancelado',
            'body' => 'O cliente cancelou essa solicitação.',
        ], $data);
    }

    public function notifyClientRequestExpired(Collection $devices, array $data): bool
    {
        return $this->sendToDevices($devices, [
            'title' => 'Busca encerrada',
            'body' => 'Ninguém aceitou em 10 minutos. Você pode pedir de novo quando quiser.',
        ], $data);
    }
}
