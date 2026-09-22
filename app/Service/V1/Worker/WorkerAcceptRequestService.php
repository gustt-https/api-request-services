<?php

namespace App\Service\V1\Worker;

use App\Enums\RequestStatus;
use App\Exceptions\Requests\ActiveServiceAlreadyExists;
use App\Exceptions\Requests\ExpiredRequest;
use App\Exceptions\Requests\FailedAcceptRequest;
use App\Http\Resources\RequestAcceptedResource;
use App\Jobs\Requests\NotifyClientWorkerAccepted;
use App\Models\RequestApplication;
use App\Models\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class WorkerAcceptRequestService
{
    public function acceptRequest(Request $request, User $worker): JsonResource
    {
        $acceptedRequest = DB::transaction(function () use ($request, $worker) {
            User::query()
                ->whereKey($worker->id)
                ->lockForUpdate()
                ->first();

            if ($worker->hasActiveWorkerService()) {
                throw new ActiveServiceAlreadyExists();
            }

            $lockRequest = Request::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();

            if ($lockRequest->status === RequestStatus::EXPIRED) throw new ExpiredRequest();
            if ($lockRequest->status !== RequestStatus::SEARCHING) throw new FailedAcceptRequest();


            $lockRequest->worker_id = $worker->id;
            $lockRequest->status = RequestStatus::ACCEPTED;
            $lockRequest->save();

            $application = new RequestApplication();
            $application->request_id = $lockRequest->id;
            $application->worker_id = $worker->id;
            $application->accepted_at = now();
            $application->save();

            return $lockRequest->load(['user', 'worker', 'worker.workerProfile']);
        });

        NotifyClientWorkerAccepted::dispatch($acceptedRequest);
        return new RequestAcceptedResource($acceptedRequest);
    }
}
