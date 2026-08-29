<?php

namespace App\Service\V1\worker;

use App\Exceptions\requests\FailedAcceptRequest;
use App\Http\Resources\RequestAcceptedResource;
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
            $request = Request::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();

            if ($request->status !== 'searching') {
                throw new FailedAcceptRequest();
            }

            $request->worker_id = $worker->id;
            $request->status = 'accepted';
            $request->save();

            $application = new RequestApplication();
            $application->request_id = $request->id;
            $application->worker_id = $worker->id;
            $application->accepted_at = now();
            $application->save();

            return $request->load(['user']);
        });

        return new RequestAcceptedResource($acceptedRequest);
    }
}
