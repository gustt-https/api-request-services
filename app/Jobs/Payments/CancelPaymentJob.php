<?php

namespace App\Jobs\Payments;

use App\Models\Payment;
use App\Service\V1\Payments\Contracts\PaymentGatewayInterface;
use App\Service\V1\Payments\PaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CancelPaymentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Payment $payment)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(PaymentService $paymentService): void
    {
        $payment = $this->payment;
        $paymentService->cancel($payment);
    }
}
