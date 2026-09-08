<?php

namespace App\Service\V1\requests;

use App\Enums\RequestStatus;
use App\Exceptions\Requests\ApplicationNotFound;
use App\Http\Resources\RequestResource;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CancelRequest
{
    public function execute(Request $request, string $reasonCancellation)
    {
        $cancelledRequest =  DB::transaction(function () use ($request, $reasonCancellation) {
            $lockedRequest = $request->newQuery()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();

            if ($lockedRequest->status === RequestStatus::IN_PROGRESS) {
                // Implementar uma exception aqui... por enquanto manter
                //(Implementar penalidade para cancelamento de requests em andamento)
            }

            $activeApplication = $lockedRequest->activeApplication();

            if (
                $activeApplication
            ) {
                $activeApplication->cancelled_at = now();
                $activeApplication->cancellation_reason = $reasonCancellation;
                $activeApplication->save();
            }

            $lockedRequest->worker_id = null;
            $lockedRequest->status = RequestStatus::CANCELED;
            $lockedRequest->save();

            return $lockedRequest->refresh();
        });

        return new RequestResource($cancelledRequest);
    }
}
