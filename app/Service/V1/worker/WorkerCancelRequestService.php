<?php

namespace App\Service\V1\worker;

use App\Exceptions\requests\ApplicationNotFound;
use App\Exceptions\requests\FailedCancelRequestService;
use App\Http\Resources\RequestResource;
use App\Jobs\NotifyWorkersOfNewRequest;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WorkerCancelRequestService
{
    public function cancelRequest(Request $request, User $worker)
    {
        $cancelledRequest = DB::transaction(function () use ($request, $worker) {
            $lockRequest = Request::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();
                
            $application = $lockRequest->activeApplication();

            if (!$application) {
                throw new ApplicationNotFound();
            }

            $application->cancelled_at = now();
            $application->save();

            $lockRequest->worker_id = null;
            $lockRequest->status = 'searching';
            $lockRequest->save();


            return $lockRequest->refresh();
        });

        NotifyWorkersOfNewRequest::dispatch($cancelledRequest);

        return new RequestResource($cancelledRequest);
    }
}
