<?php

use App\Exceptions\FailedCancelRequestService;
use App\Http\Resources\RequestResource;
use App\Jobs\NotifyWorkersOfNewRequest;
use App\Models\RequestApplication;
use App\Models\RequestService;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WorkerCancelRequestService
{
    public function cancelRequest(RequestService $request, User $worker)
    {

        if ($request->worker_id !== $worker->id) {
            throw new FailedCancelRequestService();
        }

        $cancelledRequest =  DB::transaction(function () use ($request, $worker) {
            $request->lockForUpdate();
            $request->worker_id = null;
            $request->status = 'searching';
            $request->save();

            $application = $worker->application()
                ->where('request_id', $request->id)
                ->first();

            $application->cancelled_at = now();
            $application->save();

            return $request->refresh();
        });

        NotifyWorkersOfNewRequest::dispatch($request);

        return new RequestResource($cancelledRequest);
    }
}
