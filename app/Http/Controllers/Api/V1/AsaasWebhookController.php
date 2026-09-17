<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\AsaasEvents\AsaasWebhookEventData;
use App\DTOs\AsaasEvents\AsaasWebhookPaymentData;
use App\Http\Controllers\Controller;
use App\Service\V1\Webhook\AsaasWebhookService;
use Illuminate\Http\Request;

class AsaasWebhookController extends Controller
{
    public function handle(Request $request, AsaasWebhookService $asaasService)
    {
        $payment = $request->array('payment');

        $paymentData = new AsaasWebhookPaymentData(
            $payment['object'],
            $payment['id']
        );

        $eventData = new AsaasWebhookEventData(
            $request->input('id'),
            $request->input('event'),
            $request->input('dateCreated'),
            $paymentData,
            $request->all()
        );

        $asaasService->handle($eventData);

        return response()->json([
            'message' => 'Webhook received'
        ]);
    }
}
