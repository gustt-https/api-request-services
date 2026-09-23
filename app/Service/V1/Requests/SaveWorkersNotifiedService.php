<?php

namespace App\Service\V1\Requests;

use App\Models\Device;
use App\Models\Request;
use App\Models\RequestNotification;
use Illuminate\Support\Collection;

class SaveWorkersNotifiedService
{
    public function execute(Request $request, Collection $workersNotified): void
    {
        $rows = $workersNotified
            ->map(fn ($item) => [
                'request_id' => $request->id,
                'worker_id' => $item->user_id,
                'status' => 'notified',
                'notified_at' => now(),
            ])
            // Same worker can have multiple devices; upsert rejects duplicate keys in one batch.
            ->unique('worker_id')
            ->filter(fn (array $row) => ! empty($row['worker_id']))
            ->values()
            ->all();

        if ($rows === []) {
            return;
        }

        RequestNotification::upsert(
            $rows,
            ['worker_id', 'request_id'],
            ['notified_at', 'status']
        );
    }
}
