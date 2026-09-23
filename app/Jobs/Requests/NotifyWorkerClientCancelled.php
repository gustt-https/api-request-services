<?php

namespace App\Jobs\Requests;

use App\Models\Device;
use App\Models\Request;
use App\Service\V1\Firebase\FirebaseService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyWorkerClientCancelled implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Request $request,
        protected ?int $workerId = null,
    ) {
        //
    }

    public function handle(): void
    {
        $workerIds = $this->request->notifications()
            ->pluck('worker_id');

        if ($this->workerId) {
            $workerIds->push($this->workerId);
        }

        $workerIds = $workerIds->filter()->unique()->values();

        if ($workerIds->isEmpty()) {
            return;
        }

        $devices = Device::query()
            ->active()
            ->whereIn('user_id', $workerIds)
            ->get();

        if ($devices->isEmpty()) {
            return;
        }

        $data = [
            'type' => 'request_cancelled_by_client',
            'request_id' => (string) $this->request->id,
        ];

        try {
            app(FirebaseService::class)->notifyWorkerClientCancelled($devices, $data);
        } catch (Exception $e) {
            report($e);
        }
    }
}
