<?php

namespace App\Service\V1\worker;

use App\Enums\RequestStatus;
use App\Exceptions\Requests\ApplicationNotFound;
use App\Exceptions\Requests\RequestNotInProgress;
use App\Exceptions\Requests\WorkerNotAssignedToRequest;
use App\Http\Resources\RequestResource;
use App\Jobs\NotifyClientServiceCompleted;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WorkerFinishRequestService
{
    public function execute(Request $request, User $worker)
    {
        $finshedRequest = DB::transaction(function () use ($request, $worker) {
            $lockRequest = Request::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();

            if ($lockRequest->status !== RequestStatus::IN_PROGRESS) {
                throw new RequestNotInProgress();
            }

            if ($lockRequest->worker_id !== $worker->id) {
                throw new WorkerNotAssignedToRequest();
            }

            $application = $lockRequest->activeApplication();

            if (!$application) {
                throw new ApplicationNotFound();
            }

            $lockRequest->status = RequestStatus::COMPLETED;
            $lockRequest->save();

            $application->completed_at = now();
            $application->save();

            return $lockRequest->refresh();
        });

        NotifyClientServiceCompleted::dispatch($finshedRequest);

        return new RequestResource($finshedRequest);
    }
}
