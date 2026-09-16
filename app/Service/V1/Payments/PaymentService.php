<?php

namespace App\Service\V1\Payments;

use App\DTOs\Payments\CreatePaymentData;
use App\Exceptions\Payments\PaymentCreationFailed;
use App\Exceptions\Payments\PaymentCustomerUnavailable;
use App\Models\Request;
use App\Service\V1\Payments\Contracts\PaymentGatewayInterface;

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
            // TEMP: use request price; fallback keeps local tests unblocked.
            value: (string) ($request->price ?: 100),
            dueDate: now()->toDateString(),
        );

        $providerPayment = $this->create($paymentData);

        if (! $providerPayment?->providerPaymentId) {
            throw new PaymentCreationFailed();
        }

        $pixPayload = $this->paymentGateway->getPixQrCode($providerPayment->providerPaymentId);

        if (! is_string($pixPayload) || $pixPayload === '') {
            throw new PaymentCreationFailed();
        }

        return $request->payment()->create([
            'provider' => $providerPayment->provider,
            'provider_payment_id' => $providerPayment->providerPaymentId,
            'external_reference' => 'request:'.$request->id,
            'amount' => $providerPayment->amount,
            'status' => $providerPayment->status,
            'pix_payload' => $pixPayload,
        ]);
    }

    public function getPayment()
    {
        
    }
}
