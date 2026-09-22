<?php

namespace App\Service\V1\Webhook\Payments;

use App\Enums\RequestStatus;
use App\Events\PaymentConfirmed;
use App\Exceptions\PaymentNotFound;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class ConfirmPaymentService
{
    public function confirm(Payment $payment): void
    {
        $request = DB::transaction(function () use ($payment) {
            $lockedPayment = $payment->newQuery()
                ->whereKey($payment->id)
                ->lockForUpdate()
                ->first();

            if (! $lockedPayment) {
                throw new PaymentNotFound();
            }

            if ($lockedPayment->status !== 'PENDING') {
                return;
            }

            $lockedPayment->status = 'RECEIVED';
            $lockedPayment->paid_at = now();
            $lockedPayment->save();


            $request = $lockedPayment->request;
            $request->status = RequestStatus::SEARCHING;
            $request->save();

            return $request->refresh();
        });

        if (!$request) return;

        // afterCommit: o webhook envolve este confirm() numa transaction externa.
        // Sem isso o worker pode rodar o push antes do status SEARCHING existir.
        DB::afterCommit(function () use ($request) {
            event(new PaymentConfirmed($request));
        });
    }
}
