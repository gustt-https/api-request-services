<?php

namespace App\Service\V1\worker;

use App\Enums\RequestStatus;
use App\Exceptions\Requests\FailedAcceptRequest;
use App\Http\Resources\RequestAcceptedResource;
use App\Jobs\NotifyClientWorkerAccepted;
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
            $lockRequest = Request::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();

            if ($lockRequest->status !== RequestStatus::SEARCHING) {
                throw new FailedAcceptRequest();
            }

            $lockRequest->worker_id = $worker->id;
            $lockRequest->status = RequestStatus::ACCEPTED;
            $lockRequest->save();

            $application = new RequestApplication();
            $application->request_id = $lockRequest->id;
            $application->worker_id = $worker->id;
            $application->accepted_at = now();
            $application->save();

            NotifyClientWorkerAccepted::dispatch($lockRequest);
            return $request->load(['user']);
        });

        return new RequestAcceptedResource($acceptedRequest);
    }
}
