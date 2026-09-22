<?php

namespace App\Listeners\Payments;

use App\Enums\PaymentStatus;
use App\Events\Requests\RequestCanceled;
use App\Jobs\Payments\CancelPaymentJob;
use App\Jobs\Payments\RefundPaymentJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleCanceledPayment
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
        $request = $event->request;

        if (!$payment = $request->payment) return;

        match ($payment->status) {
            PaymentStatus::PENDING => CancelPaymentJob::dispatch($payment),
            PaymentStatus::RECEIVED => RefundPaymentJob::dispatch($payment)
        };
    }
}
