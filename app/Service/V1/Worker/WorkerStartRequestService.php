<?php

namespace App\Service\V1\Worker;

use App\Enums\RequestStatus;
use App\Exceptions\Requests\ApplicationNotFound;
use App\Exceptions\Requests\InvalidSecurityCode;
use App\Exceptions\Requests\RequestNotAccepted;
use App\Exceptions\Requests\SecurityCodeAlreadyUsed;
use App\Exceptions\Requests\SecurityCodeNotFound;
use App\Exceptions\Requests\WorkerNotAssignedToRequest;
use App\Http\Resources\RequestResource;
use App\Jobs\Requests\NotifyClientServiceStarted;
use App\Models\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class WorkerStartRequestService
{
    public function execute(Request $request, User $worker, string $code): JsonResource
    {
        $startedRequest =  DB::transaction(function () use ($request, $worker, $code) {

            $requestLock = Request::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();

            if ($requestLock->status !== RequestStatus::ACCEPTED) {
                throw new RequestNotAccepted();
            }

            if (
                $requestLock->worker_id !== $worker->id
            ) {
                throw new WorkerNotAssignedToRequest();
            }

            $securiyCode = $requestLock->securityCode()
                ->lockForUpdate()
                ->first();

            if (!$securiyCode) {
                throw new SecurityCodeNotFound();
            }

            if (!is_null($securiyCode->used_at)) {
                throw new SecurityCodeAlreadyUsed();
            }

            if (! hash_equals((string) $securiyCode->code, $code)) {
                throw new InvalidSecurityCode();
            }

            $securiyCode->used_at = now();
            $securiyCode->save();

            $requestLock->status = RequestStatus::IN_PROGRESS;
            $requestLock->save();

            $application = $requestLock->activeApplication();

            if (!$application) {
                throw new ApplicationNotFound();
            }

            $application->started_at = now();
            $application->save();

            return $requestLock->refresh();
        });

        NotifyClientServiceStarted::dispatch($startedRequest);

        return new RequestResource($startedRequest);
    }
}
