<?php

namespace App\Service\V1\Payments;

use App\DTOs\Payments\CreatePaymentData;
use App\DTOs\Payments\PaymentData;
use App\Exceptions\Payments\PaymentCreationFailed;
use App\Jobs\RetryFetchPaymentPix;
use App\Models\Payment;
use App\Models\Request;
use App\Service\V1\Payments\Contracts\CustomerGatewayInterface;
use App\Service\V1\Payments\Contracts\PaymentGatewayInterface;
use Throwable;

class PaymentService
{

    public function __construct(
        private PaymentGatewayInterface $paymentGateway,
    ) {}

    public function process(Payment $payment, string $customerId)
    {
        $data = new CreatePaymentData(
            customer: $customerId,
            billingType: 'PIX',
            value: $payment->amount,
            dueDate: now()->toDateString()
        );

        $providerPayment = $this->paymentGateway->createPayment($data);

        if (! $providerPayment) throw new PaymentCreationFailed();

        $payment->provider_payment_id = $providerPayment->providerPaymentId;
        $payment->amount = $providerPayment->amount;
        $payment->status = $providerPayment->status;
        $payment->save();

        try {
            $payloadPix = $this->paymentGateway->getPixQrCode($providerPayment->providerPaymentId);
            $payment->update(['pix_payload' => $payloadPix]);
            return $payment->refresh();
            
        } catch (Throwable $e) {
            RetryFetchPaymentPix::dispatch($payment->id);
        }
    }
}
