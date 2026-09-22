<?php

namespace App\Listeners\Requests;

use App\Events\Requests\RequestCanceled;
use App\Jobs\Requests\NotifyClientWorkerCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyWorkerClientCancelled
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
    public function handle(RequestCanceled $event): void
    {
        NotifyClientWorkerCancelled::dispatch($event->request);
    }
}
