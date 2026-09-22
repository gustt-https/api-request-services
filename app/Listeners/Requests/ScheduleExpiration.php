<?php

namespace App\Listeners\Requests;

use App\Events\Payments\PaymentConfirmed;
use App\Jobs\Requests\NotifyClientRequestExpired;
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
