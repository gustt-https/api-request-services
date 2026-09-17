<?php

namespace App\Jobs;

use App\Models\Request;
use App\Service\V1\firebase\FirebaseService;
use App\Service\V1\requests\FindClientOfRequest;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyClientPaymentConfirmedJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Request $request)
    {
        //
    }

    public function handle(FindClientOfRequest $client): void
    {
        $owner = $client->find($this->request);
        $devices = $owner->devices()->active()->get();

        if ($devices->isEmpty()) {
            return;
        }

        $data = [
            'type' => 'payment_confirmed',
            'request_id' => (string) $this->request->id,
        ];

        try {
            app(FirebaseService::class)->notifyClientPaymentConfirmed($devices, $data);
        } catch (Exception $e) {
            report($e);
        }
    }
}
