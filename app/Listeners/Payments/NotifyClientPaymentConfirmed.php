<?php

namespace App\Listeners\Payments;

use App\Events\Payments\PaymentConfirmed;
use App\Jobs\Payments\NotifyClientPaymentConfirmedJob;
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
