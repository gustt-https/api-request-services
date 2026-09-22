<?php

namespace App\Service\V1\Payments;

use App\DTOs\Payments\CreatePaymentData;
use App\DTOs\Payments\PaymentData;
use App\Enums\PaymentStatus;
use App\Exceptions\Payments\PaymentCreationFailed;
use App\Jobs\Payments\RetryFetchPaymentPix;
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
        $payment->refresh();

        if ($payment->status !== PaymentStatus::PENDING) {
            return;
        }

        $providerPayment = $this->findPaymentByExternalReference($payment);

        if (! $providerPayment) {
            $providerPayment = $this->paymentGateway->createPayment(new CreatePaymentData(
                customer: $customerId,
                externalReference: $payment->external_reference,
                billingType: 'PIX',
                value: $payment->amount,
                dueDate: now()->toDateString()
            ));
        }

        if (! $providerPayment) throw new PaymentCreationFailed();

        $payment->refresh();

        if ($payment->status !== PaymentStatus::PENDING) {
            $this->paymentGateway->cancelPayment($providerPayment->providerPaymentId);
            return;
        }

        $payment->provider_payment_id = $providerPayment->providerPaymentId;
        $payment->amount = $providerPayment->amount;
        $payment->status = PaymentStatus::fromProvider($providerPayment->status);
        $payment->save();

        try {
            $payloadPix = $this->paymentGateway->getPixQrCode($providerPayment->providerPaymentId);
            $payment->update(['pix_payload' => $payloadPix]);
            return $payment->refresh();
        } catch (Throwable $e) {
            RetryFetchPaymentPix::dispatch($payment->id);
        }
    }

    public function cancel(Payment $payment)
    {
        if ($payment->status !== PaymentStatus::PENDING) return;

        $providerPaymentId = $this->getProviderId($payment);

        if (! $providerPaymentId) {
            $this->markCanceled($payment);
            return;
        }

        $paymentCancelled  = $this->paymentGateway->cancelPayment($providerPaymentId);

        if (! $paymentCancelled->deleted) return;

        $this->markCanceled($payment);
    }


    private function getProviderId(Payment $payment): ?string
    {
        return $payment->provider_payment_id ?? null;
    }

    private function markCanceled(Payment $payment): void
    {
        $payment->status = PaymentStatus::CANCELED;
        $payment->save();
    }


    private function findPaymentByExternalReference(Payment $payment): ?PaymentData
    {
        return $this->paymentGateway->getPayment($payment->external_reference);
    }
}
