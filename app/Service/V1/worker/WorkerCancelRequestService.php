<?php

namespace App\Service\V1\worker;

use App\Enums\RequestStatus;
use App\Exceptions\Requests\ApplicationNotFound;
use App\Exceptions\Requests\FailedCancelRequest;
use App\Exceptions\Requests\WorkerNotAssignedToRequest;
use App\Http\Resources\RequestResource;
use App\Jobs\NotifyClientWorkerCancelled;
use App\Jobs\NotifyWorkersOfNewRequest;
use App\Models\Request;
use App\Models\User;
use App\Service\V1\requests\ResolveSearchRadiusService;
use Illuminate\Support\Facades\DB;

class WorkerCancelRequestService
{
    public function __construct(protected ResolveSearchRadiusService $resolveRadius) {}

    public function cancelRequest(Request $request, User $worker)
    {
        $cancelledRequest = DB::transaction(function () use ($request, $worker) {
            $lockRequest = Request::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();

            if ($lockRequest->worker_id !== $worker->id) {
                throw new WorkerNotAssignedToRequest();
            }

            if ($lockRequest->status !== RequestStatus::ACCEPTED) {
                throw new FailedCancelRequest();
            }

            $application = $lockRequest->activeApplication();

            if (!$application) {
                throw new ApplicationNotFound();
            }

            $application->cancelled_at = now();
            $application->save();

            $lockRequest->worker_id = null;
            $lockRequest->status = RequestStatus::SEARCHING;
            $lockRequest->save();

            return $lockRequest->refresh();
        });

        NotifyClientWorkerCancelled::dispatch($cancelledRequest);

        // Resetando o raio de busca antes de disparar...
        $this->resolveRadius->forget($cancelledRequest);
        NotifyWorkersOfNewRequest::dispatch($cancelledRequest);

        return new RequestResource($cancelledRequest);
    }
}
