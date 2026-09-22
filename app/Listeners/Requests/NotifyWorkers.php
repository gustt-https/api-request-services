<?php

namespace App\Listeners\Requests;

use App\Events\Payments\PaymentConfirmed;
use App\Jobs\Requests\NotifyWorkersOfNewRequest;
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
    public function handle(PaymentConfirmed $event): void
    {
        NotifyWorkersOfNewRequest::dispatch($event->request);
    }
}
