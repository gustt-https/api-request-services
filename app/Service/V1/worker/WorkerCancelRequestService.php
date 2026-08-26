<?php

namespace App\Service\V1\worker;

use App\Exceptions\FailedCancelRequestService;
use App\Http\Resources\RequestResource;
use App\Jobs\NotifyWorkersOfNewRequest;
use App\Models\RequestService;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WorkerCancelRequestService
{
    public function cancelRequest(RequestService $request, User $worker)
    {
        $cancelledRequest = DB::transaction(function () use ($request, $worker) {
            $request = RequestService::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();

            if ($request->worker_id !== $worker->id) {
                throw new FailedCancelRequestService();
            }

            $request->worker_id = null;
            $request->status = 'searching';
            $request->accepted_at = null;
            $request->save();

            $application = $worker->application()
                ->where('request_id', $request->id)
                ->whereNull('cancelled_at')
                ->latest('id')
                ->first();

            $application->status = 'cancelled';
            $application->cancelled_at = now();
            $application->save();

            return $request->refresh();
        });

        NotifyWorkersOfNewRequest::dispatch($cancelledRequest);

        return new RequestResource($cancelledRequest);
    }
}
