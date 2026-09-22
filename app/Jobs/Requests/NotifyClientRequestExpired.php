<?php

namespace App\Jobs\Requests;

use App\Enums\RequestStatus;
use App\Models\Request;
use App\Service\V1\Firebase\FirebaseService;
use App\Service\V1\Requests\FindClientOfRequest;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class NotifyClientRequestExpired implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Request $request) {}

    public function handle(FindClientOfRequest $clientRequest)
    {
        $this->request->refresh();

        if (
            $this->request->status === RequestStatus::SEARCHING
        ) {
            $expiredRequest =  DB::transaction(function () {
                $locked = $this->request->newQuery()
                    ->where('id', $this->request->id)
                    ->where('status', RequestStatus::SEARCHING)
                    ->lockForUpdate()
                    ->first();

                if (!$locked) return;


                $locked->status = RequestStatus::EXPIRED;
                $locked->save();

                return $locked;
            });


            if (!$expiredRequest) return;


            $client = $clientRequest->find($expiredRequest);
            $devices = $client->devices()->active()->get();

            if ($devices->isEmpty()) return;

            $data = $this->buildNotificationData();

            try {
                app(FirebaseService::class)->notifyClientRequestExpired($devices, $data);
            } catch (Exception $e) {
                report($e);
            }
        }
    }

    private function buildNotificationData()
    {
        return [
            'type' => 'request_expired',
            'request_id' => $this->request->id
        ];
    }
}
