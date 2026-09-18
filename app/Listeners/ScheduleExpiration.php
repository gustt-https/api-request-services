<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Jobs\NotifyClientRequestExpired;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ScheduleExpiration
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
        NotifyClientRequestExpired::dispatch($event->request)->delay(now()->addMinutes(10));
        
    }
}
