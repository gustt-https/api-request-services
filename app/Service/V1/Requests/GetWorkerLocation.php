<?php

namespace App\Service\V1\Requests;

use App\Enums\RequestStatus;
use App\Exceptions\Requests\RequestNotAccepted;
use App\Http\Resources\WorkerLocationResource;
use App\Models\Request;

class GetWorkerLocation
{
    public function execute(Request $request)
    {
        if (
            $request->status !== RequestStatus::ACCEPTED
        ) {
            throw new RequestNotAccepted();
        }

        $worker = $request->load(['worker.workerProfile']);

        return new WorkerLocationResource($worker);
    }
}
