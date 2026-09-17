<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Service\V1\Payments\Contracts\PaymentGatewayInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RetryFetchPaymentPix implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $paymentId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(PaymentGatewayInterface $gateway): void
    {
        $payment = Payment::query()
            ->whereKey($this->paymentId)
            ->first();

        if (!$payment) return;

        if($payment->pix_payload) return;

        $pixPayload =  $gateway->getPixQrCode($payment->provider_payment_id);

        $payment->pix_payload = $pixPayload;
        $payment->save();
    }
}
