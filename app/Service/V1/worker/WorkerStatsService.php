<?php

namespace App\Service\V1\worker;

use App\Models\RequestApplication;
use App\Models\User;

class WorkerStatsService
{
    /**
     * Lifetime totals for the worker profile screen. One aggregate query so
     * /me stays cheap no matter how long the history gets.
     *
     * @return array{services_completed: int, total_earned: string}
     */
    public function forWorker(User $worker): array
    {
        $totals = RequestApplication::query()
            // Both tables carry worker_id, so every column stays qualified.
            ->where('request_applications.worker_id', $worker->id)
            ->whereNotNull('request_applications.completed_at')
            ->join('requests', 'requests.id', '=', 'request_applications.request_id')
            ->selectRaw('COUNT(*) as services_completed, COALESCE(SUM(requests.price), 0) as total_earned')
            ->first();

        return [
            'services_completed' => (int) ($totals?->services_completed ?? 0),
            'total_earned' => (string) ($totals?->total_earned ?? '0'),
        ];
    }
}
