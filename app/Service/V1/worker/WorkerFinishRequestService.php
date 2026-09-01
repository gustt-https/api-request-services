<?php

namespace App\Service\V1\worker;

use App\Enums\RequestStatus;
use App\Exceptions\requests\ApplicationNotFound;
use App\Exceptions\requests\WorkerNotAssignedToRequest;
use App\Http\Resources\RequestResource;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WorkerFinishRequestService
{
    public function execute(Request $request, User $worker)
    {

        $finshedRequest =   DB::transaction(function () use ($request, $worker) {
            $lockRequest = Request::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();

            if (
                $lockRequest->status !== RequestStatus::IN_PROGRESS
            ) {
                throw new ApplicationNotFound();
            }

            $lockRequest->status = RequestStatus::COMPLETED;
            $lockRequest->save();

            $application = $lockRequest->activeApplication();

            if (
                !$application
            ) {
                return;
            }

            $application->completed_at = now();
            $application->save();

            return $lockRequest->refresh();
        });

        return new RequestResource($finshedRequest);
    }
}
