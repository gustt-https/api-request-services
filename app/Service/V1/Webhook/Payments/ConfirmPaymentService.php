<?php

namespace App\Service\V1\Webhook\Payments;

use App\Enums\PaymentStatus;
use App\Enums\RequestStatus;
use App\Events\Payments\PaymentConfirmed;
use App\Exceptions\Payments\PaymentNotFound;
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

            $request = $lockedPayment->request;

            if (
                $lockedPayment->status !== PaymentStatus::PENDING
                || $request->status !== RequestStatus::AWAIT_PAYMENT
            ) {
                return;
            }

            $request->status = RequestStatus::SEARCHING;
            $request->save();

            $lockedPayment->status = PaymentStatus::RECEIVED;
            $lockedPayment->paid_at = now();
            $lockedPayment->save();

            return $request->refresh();
        });

        if (!$request) return;

        DB::afterCommit(function () use ($request) {
            event(new PaymentConfirmed($request));
        });
    }
}
