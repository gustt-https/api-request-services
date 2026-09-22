<?php

namespace App\Jobs\Payments;

use App\Models\Request;
use App\Service\V1\Firebase\FirebaseService;
use App\Service\V1\Requests\FindClientOfRequest;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyClientPixReady implements ShouldQueue
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
            'type' => 'pix_ready',
            'request_id' => (string) $this->request->id,
        ];

        try {
            app(FirebaseService::class)->notifyClientPixReady($devices, $data);
        } catch (Exception $e) {
            report($e);
        }
    }
}
