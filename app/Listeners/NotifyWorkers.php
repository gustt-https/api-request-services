<?php

namespace App\Listeners;

use App\Events\RequestCreated;
use App\Jobs\NotifyWorkersOfNewRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyWorkers
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RequestCreated $event): void
    {
        NotifyWorkersOfNewRequest::dispatch($event->request);
    }
}
