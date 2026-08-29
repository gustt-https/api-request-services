<?php

namespace App\Service\V1\requests;

use App\Models\Device;
use App\Models\Request;
use App\Models\RequestNotification;
use Illuminate\Support\Collection;

class SaveWorkersNotifiedService
{
    public function execute(Request $request, Collection $workersNotified): void
    {
        $workersNotifiedColletct = $workersNotified->map(fn($item) => [
            'request_id' => $request->id,
            'worker_id' => $item->user_id,
            'status' => 'notified',
            'notified_at' => now()
        ])->toArray();

        RequestNotification::insert($workersNotifiedColletct);
    }
}
