<?php

namespace App\Service\V1\Requests;

use App\Enums\RequestStatus;
use App\Events\Requests\RequestCanceled;
use App\Exceptions\Requests\FailedCancelRequest;
use App\Http\Resources\RequestResource;
use App\Models\Request;
use Illuminate\Support\Facades\DB;

class CancelRequest
{
    public function execute(Request $request, string $reasonCancellation)
    {
        $assignedWorkerId = null;

        $cancelledRequest = DB::transaction(function () use ($request, $reasonCancellation, &$assignedWorkerId) {
            $lockedRequest = $request->newQuery()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();

            if (! in_array($lockedRequest->status, [
                RequestStatus::SEARCHING,
                RequestStatus::ACCEPTED,
                RequestStatus::AWAIT_PAYMENT
            ], true)) {
                throw new FailedCancelRequest(
                    'Não é possível cancelar um serviço em andamento ou já finalizado.'
                );
            }

            $assignedWorkerId = $lockedRequest->worker_id;
            $activeApplication = $lockedRequest->activeApplication();

            if ($activeApplication) {
                $activeApplication->cancelled_at = now();
                $activeApplication->cancellation_reason = $reasonCancellation;
                $activeApplication->save();
            }

            $lockedRequest->worker_id = null;
            $lockedRequest->status = RequestStatus::CANCELED;
            $lockedRequest->save();
            return $lockedRequest->refresh();
        });

        event(new RequestCanceled($cancelledRequest, $assignedWorkerId));

        return new RequestResource($cancelledRequest);
    }
}
