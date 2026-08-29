<?php

namespace App\Jobs;

use App\Models\Request;
use App\Service\V1\firebase\FirebaseService;
use App\Service\V1\requests\FindNearbyWorkersService;
use App\Service\V1\requests\SaveWorkersNotifiedService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class NotifyWorkersOfNewRequest implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Request $request
    ) {}

    public function handle(
        FindNearbyWorkersService $workers,
        SaveWorkersNotifiedService $saveWorkersNotified,
    ): void {
        $devices = $workers->find($this->request, 5);

        // Persist first — preview policy requires the notification row even if FCM fails.
        if ($devices->isNotEmpty()) {
            $saveWorkersNotified->execute($this->request, $devices);
        }

        if ($devices->isEmpty()) {
            return;
        }

        try {
            app(FirebaseService::class)->sendNewRequestPush(
                $devices,
                $this->buildNotificationData(),
            );
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function buildNotificationData(): array
    {
        return [
            'type' => 'new_request',
            'request_id' => (string) $this->request->id,
        ];
    }
}
