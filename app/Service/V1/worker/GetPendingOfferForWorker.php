<?php

namespace App\Service\V1\worker;

use App\Enums\RequestStatus;
use App\Models\Request;
use App\Models\RequestNotification;
use App\Models\User;

/**
 * Latest searching offer this worker was notified about.
 * Used when the app resumes without the worker tapping the FCM tray item —
 * Android does not deliver notification+data payloads to JS until tap.
 */
class GetPendingOfferForWorker
{
    public function execute(User $worker): ?Request
    {
        $onJob = Request::query()
            ->where('worker_id', $worker->id)
            ->whereIn('status', [
                RequestStatus::ACCEPTED,
                RequestStatus::IN_PROGRESS,
            ])
            ->exists();

        if ($onJob) {
            return null;
        }

        $notification = RequestNotification::query()
            ->where('worker_id', $worker->id)
            ->where('status', 'notified')
            ->whereHas('request', function ($query) use ($worker) {
                $query
                    ->where('status', RequestStatus::SEARCHING)
                    ->whereDoesntHave('applications', function ($applications) use ($worker) {
                        $applications->where('worker_id', $worker->id);
                    });
            })
            ->orderByDesc('notified_at')
            ->first();

        return $notification?->request;
    }
}
