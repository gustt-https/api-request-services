<?php

namespace App\Service\V1\worker;

use App\Exceptions\requests\ApplicationNotFound;
use App\Exceptions\requests\InvalidSecurityCode;
use App\Exceptions\requests\RequestNotAccepted;
use App\Exceptions\requests\SecurityCodeAlreadyUsed;
use App\Exceptions\requests\SecurityCodeNotFound;
use App\Exceptions\requests\WorkerNotAssignedToRequest;
use App\Http\Resources\RequestResource;
use App\Models\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WorkerStartRequestService
{
    public function execute(Request $request, User $worker, string $code): JsonResource
    {
        $startedRequest =  DB::transaction(function () use ($request, $worker, $code) {

            $requestLock = Request::query()
                ->whereKey($request->id)
                ->lockForUpdate()
                ->first();

            if ($requestLock->status !== 'accepted') {
                throw new RequestNotAccepted();
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

            if (!Hash::check($code, $securiyCode->code)) {
                throw new InvalidSecurityCode();
            }

            $securiyCode->used_at = now();
            $securiyCode->save();

            $requestLock->status = 'in_progress';
            $requestLock->save();

            $application = $requestLock->activeApplication();

            if (!$application) {
                throw new ApplicationNotFound();
            }

            $application->started_at = now();
            $application->save();

            return $requestLock->refresh();
        });

        return new RequestResource($startedRequest);
    }
}
