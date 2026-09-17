<?php

namespace App\Service\V1\Webhook;

use App\DTOs\AsaasEvents\AsaasWebhookEventData;
use App\Models\Payment;
use App\Models\PaymentEvent;
use App\Service\V1\Webhook\Payments\ConfirmPaymentService;
use Illuminate\Support\Facades\DB;

class AsaasWebhookService
{
    public function __construct(public ConfirmPaymentService $paymentConfirm) {}

    public function handle(AsaasWebhookEventData $data)
    {


        DB::transaction(function () use ($data) {

            $payment = Payment::query()
                ->where('provider_payment_id', $data->payment->id)
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                abort(503);
            }
            $alreadyProcessed = PaymentEvent::query()
                ->where('provider_event_id', $data->id)
                ->first();

            if ($alreadyProcessed) {
                return;
            }

            $payment->events()->create([
                'provider' =>  'asaas',
                'provider_event_id' => $data->id,
                'event' => $data->event,
                'payload' => $data->payload
            ]);

            $event = $data->event;

            match ($event) {
                'PAYMENT_RECEIVED' => $this->paymentConfirm->confirm($payment),
                default => null
            };
        });
    }
}
