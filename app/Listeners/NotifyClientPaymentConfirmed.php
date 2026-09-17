<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Jobs\NotifyClientPaymentConfirmedJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyClientPaymentConfirmed
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
        NotifyClientPaymentConfirmedJob::dispatch($event->request);
    }
}
