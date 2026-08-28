<?php

namespace App\Jobs;

use App\Models\Request;
use App\Service\V1\firebase\FirebaseService;
use App\Service\V1\requests\FindNearbyWorkersService;
use App\Service\V1\requests\SaveWorkersNotifiedService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;


class NotifyWorkersOfNewRequest implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Request $request
    ) {}


    public function handle(
        FirebaseService $firebase,
        FindNearbyWorkersService $workers,
        SaveWorkersNotifiedService $saveWorkersNotified
    ): void {

        $workesInRadius = $workers->find($this->request, 5);
        $data = $this->buildNotificationData();

        $firebase->sendNewRequestPush(
            $workesInRadius,
            $data
        );

        $saveWorkersNotified->execute(
            $this->request,
            $workesInRadius
        );
    }

    private function buildNotificationData()
    {
        return [
            'type' => 'new_request',
            'request_id' => (string) $this->request->id
        ];
    }
}
