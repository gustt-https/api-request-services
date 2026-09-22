<?php

namespace App\Jobs\Requests;

use App\Models\Request;
use App\Service\V1\Firebase\FirebaseService;
use App\Service\V1\Requests\FindClientOfRequest;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyClientWorkerAccepted implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Request $request)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        FindClientOfRequest $client,
    ): void {

        $client = $client->find($this->request);
        $devices = $client->devices()->active()->get();

        if (
            $devices->isEmpty()
        ) {
            return;
        }

        $data = $this->buildNotificationData();

        try {
            app(FirebaseService::class)->notifyClientWorkerAccepted($devices, $data);
        } catch (
            Exception $e
        ) {
            report($e);
        }
    }

    private function buildNotificationData()
    {
        return [
            'type' => 'request_accepted',
            'request_id' => $this->request->id
        ];
    }
}
