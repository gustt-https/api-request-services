<?php

namespace App\Service\V1\Payments;

use App\DTOs\Payments\CreatePaymentData;
use App\Exceptions\Payments\PaymentCreationFailed;
use App\Exceptions\Payments\PaymentCustomerUnavailable;
use App\Jobs\RetryFetchPaymentPix;
use App\Models\Request;
use App\Service\V1\Payments\Contracts\PaymentGatewayInterface;
use Throwable;

class PaymentService
{
    public function __construct(
        private PaymentGatewayInterface $paymentGateway,
        private CustomerService $customerService
    ) {}

    public function create(CreatePaymentData $data)
    {
        return $this->paymentGateway->createPayment($data);
    }

    public function createForRequest(Request $request)
    {
        $user = $request->user;
        $customerId = $this->customerService->getOrCreate($user);

        if (! $customerId) {
            throw new PaymentCustomerUnavailable();
        }

        $paymentData = new CreatePaymentData(
            customer: $customerId,
            billingType: 'PIX',
            value: number_format((float) $request->price, 2, '.', ''),
            dueDate: now()->toDateString(),
        );

        $providerPayment = $this->create($paymentData);

        if (! $providerPayment?->providerPaymentId) {
            throw new PaymentCreationFailed();
        }

        $payment = $request->payment()->create([
            'provider' => $providerPayment->provider,
            'provider_payment_id' => $providerPayment->providerPaymentId,
            'external_reference' => 'request:' . $request->id,
            'amount' => $providerPayment->amount,
            'status' => $providerPayment->status,
        ]);

        try {

            $pixPayload = $this->paymentGateway->getPixQrCode($providerPayment->providerPaymentId);

            $payment->update([
                'pix_payload' => $pixPayload
            ]);
        } catch (Throwable $e) {
            RetryFetchPaymentPix::dispatch($payment->id);
        }

        return $payment;
    }
}
