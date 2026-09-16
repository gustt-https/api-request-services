<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\RequestStatus;
use App\Events\RequestCreated;
use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Service\V1\Payments\Client\AsaasClient;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * TEMP: no ownership check — sandbox validation only.
     * Also refreshes Asaas status and advances await_payment → searching when paid.
     */
    public function show(Request $requestService, AsaasClient $asaas)
    {
        $payment = $requestService->payment;

        if ($payment?->provider_payment_id) {
            try {
                $remote = $asaas->get("payments/{$payment->provider_payment_id}");
                $remoteStatus = isset($remote['status']) ? (string) $remote['status'] : null;

                if ($remoteStatus && $remoteStatus !== $payment->status) {
                    $payment->status = $remoteStatus;

                    if (in_array($remoteStatus, ['RECEIVED', 'CONFIRMED', 'RECEIVED_IN_CASH'], true)) {
                        $payment->paid_at = now();
                    }

                    $payment->save();
                }
            } catch (\Throwable $e) {
                Log::warning('TEMP payment status refresh failed', [
                    'payment_id' => $payment->id,
                    'message' => $e->getMessage(),
                ]);
            }

            if (
                in_array($payment->status, ['RECEIVED', 'CONFIRMED', 'RECEIVED_IN_CASH'], true)
                && $requestService->status === RequestStatus::AWAIT_PAYMENT
            ) {
                $requestService->status = RequestStatus::SEARCHING;
                $requestService->save();
                event(new RequestCreated($requestService->fresh()));
            }
        }

        return response()->json([
            'data' => $payment,
        ]);
    }
}
