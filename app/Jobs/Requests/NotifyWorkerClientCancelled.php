<?php

namespace App\Jobs\Requests;

use App\Models\Request;
use App\Service\V1\Firebase\FirebaseService;
use App\Service\V1\Requests\FindWorkerOfRequest;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyWorkerClientCancelled implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Request $request,
        protected int $workerId,
    ) {
        //
    }

    public function handle(
        FindWorkerOfRequest $worker,
    ): void {

        $worker = $worker->find($this->workerId);
        $devices = $worker->devices()->active()->get();

        if (
            $devices->isEmpty()
        ) {
            return;
        }

        $data = $this->buildNotificationData();

        try {
            app(FirebaseService::class)->notifyWorkerClientCancelled($devices, $data);
        } catch (
            Exception $e
        ) {
            report($e);
        }
    }

    private function buildNotificationData()
    {
        return [
            'type' => 'request_cancelled_by_client',
            'request_id' => $this->request->id
        ];
    }
}
