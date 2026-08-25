<?php

namespace App\Jobs;

use App\Models\RequestService;
use App\Service\V1\firebase\FirebaseService;
use App\Service\V1\requests\FindNearbyWorkersService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;


class NotifyWorkersOfNewRequest implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected RequestService $request) {}

    /**
     * Execute the job.
     */

    public function handle(FirebaseService $firebase, FindNearbyWorkersService $workers): void
    {
   

        $workesInRadius = $workers->find($this->request, 5);
        $data = $this->buildNotificationData();

        $firebase->sendNewRequestPush($workesInRadius, $data);
    }

    private function buildNotificationData()
    {
        return [
            'type' => 'new_request',
            'request_id' => (string) $this->request->id
        ];
    }
}
