<?php

namespace App\Service\V1\Webhook;

use App\DTOs\AsaasEvents\AsaasWebhookEventData;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Service\V1\Webhook\Payments\ConfirmPaymentService;

class AsaasWebhookService
{
    public function __construct(public ConfirmPaymentService $paymentConfirm) {}

    public function handle(AsaasWebhookEventData $data)
    {
        $alreadyProcessed = PaymentEvent::query()
            ->where('provider_event_id', $data->id)
            ->exists();

        if ($alreadyProcessed) {
            return;
        }

        $payment = Payment::query()
            ->where('provider_payment_id', $data->payment->id)
            ->first();

        if (! $payment) {
            return;
        }

        switch ($data->event) {
            case 'PAYMENT_RECEIVED':
                $this->paymentConfirm->confirm($payment);
                break;
        }
    }
}
