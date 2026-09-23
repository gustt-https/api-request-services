<?php

namespace App\Listeners\Requests;

use App\Events\Requests\RequestCanceled;
use App\Jobs\Requests\NotifyWorkerClientCancelled as NotifyWorkerClientCancelledJob;

class NotifyWorkerClientCancelled
{
    public function handle(RequestCanceled $event): void
    {
        NotifyWorkerClientCancelledJob::dispatch(
            $event->request,
            $event->assignedWorkerId,
        );
    }
}
