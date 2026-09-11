<?php

namespace App\Jobs;

use App\Enums\RequestStatus;
use App\Models\Request;
use App\Service\V1\firebase\FirebaseService;
use App\Service\V1\requests\FindNearbyWorkersService;
use App\Service\V1\requests\ResolveSearchRadiusService;
use App\Service\V1\requests\SaveWorkersNotifiedService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class NotifyWorkersOfNewRequest implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Request $request
    ) {}

    public function handle(
        FindNearbyWorkersService $workers,
        SaveWorkersNotifiedService $saveWorkersNotified,
        ResolveSearchRadiusService $radiusResolve
    ): void {

        $this->request->refresh();
        if ($this->request->status !== RequestStatus::SEARCHING) return;

        $radius = $radiusResolve->resolve($this->request);
        $devices = $workers->find($this->request, $radius);

        if ($devices->isNotEmpty()) {
            $saveWorkersNotified->execute($this->request, $devices);
        }

        try {
            app(FirebaseService::class)->sendNewRequestPush(
                $devices,
                $this->buildNotificationData(),
            );
        } catch (Throwable $exception) {
            report($exception);
        }

        $this->request->refresh();

        if (
            $this->request->status === RequestStatus::SEARCHING
            && $radius === $radiusResolve::DEFAULT_RADIUS
        ) {
            self::dispatch($this->request)->delay(now()->addMinutes(5));
        }
    }

    private function buildNotificationData(): array
    {
        return [
            'type' => 'new_request',
            'request_id' => (string) $this->request->id,
        ];
    }
}
