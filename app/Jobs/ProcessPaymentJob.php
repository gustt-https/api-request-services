<?php

namespace App\Jobs;

use App\Exceptions\Payments\PaymentCreationFailed;
use App\Models\Request;
use App\Service\V1\Payments\CustomerService;
use App\Service\V1\Payments\PaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPaymentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Request $request)
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(CustomerService $customerService, PaymentService $paymentService): void
    {   
        $request = $this->request->refresh();
        $payment = $request->payment;

        if (! $payment) {
            throw new PaymentCreationFailed();
        }

        if ($payment->provider_payment_id) {
            if (! $payment->pix_payload) {
                RetryFetchPaymentPix::dispatch($payment->id);
            }

            return;
        }

        $customer = $customerService->getOrCreate($request->user);

        if (! $customer) {
            throw new PaymentCreationFailed();
        }

        $processed = $paymentService->process($payment, $customer);

        if ($processed?->pix_payload) {
            NotifyClientPixReady::dispatch($request);
        }
    }
}
