<?php

namespace App\Service\V1\Webhook\Payments;

use App\Enums\RequestStatus;
use App\Events\PaymentConfirmed;
use App\Events\RequestCreated;
use App\Exceptions\PaymentCannotBeConfirmed;
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
                throw new PaymentCannotBeConfirmed();
            }

            $lockedPayment->status = 'RECEIVED';
            $lockedPayment->paid_at = now();
            $lockedPayment->save();

            $request = $lockedPayment->request;
            $request->status = RequestStatus::SEARCHING;
            $request->save();

            return $request->refresh();
        });

        event(new PaymentConfirmed($request));
        // Start worker search only after Pix clears.
        event(new RequestCreated($request));
    }
}
