<?php

namespace App\Console\Commands;

use App\Enums\RequestStatus;
use App\Jobs\NotifyWorkersOfNewRequest;
use App\Models\Request;
use App\Models\ServicePackage;
use App\Models\User;
use App\Models\WorkerProfile;
use App\Service\V1\requests\GenerateSecurityCodeService;
use Illuminate\Console\Command;

class CreateDemoRequestCommand extends Command
{
    protected $signature = 'demo:request
                            {worker=6 : Worker user_id that must receive the push}
                            {--client=5 : Client user_id that owns the request}
                            {--sync : Send the push in-process instead of queueing}';

    protected $description = 'Create a searching request near a worker and notify them (local testing).';

    public function handle(GenerateSecurityCodeService $generateCode): int
    {
        $workerId = (int) $this->argument('worker');
        $clientId = (int) $this->option('client');

        $worker = WorkerProfile::query()->where('user_id', $workerId)->first();
        if (! $worker) {
            $this->error("Worker profile not found for user {$workerId}.");

            return self::FAILURE;
        }

        $client = User::query()->find($clientId);
        if (! $client) {
            $this->error("Client user {$clientId} not found.");

            return self::FAILURE;
        }

        // Free the client so makeRequest rules don't block a fresh demo.
        Request::query()
            ->where('user_id', $clientId)
            ->whereIn('status', [
                RequestStatus::SEARCHING,
                RequestStatus::ACCEPTED,
                RequestStatus::IN_PROGRESS,
            ])
            ->update(['status' => RequestStatus::CANCELED]);

        $lat = (string) ($worker->latitude ?: '-10.89468790');
        $lng = (string) ($worker->longitude ?: '-37.09853720');

        $package = ServicePackage::query()->active()->where('slug', 'economico')->first()
            ?? ServicePackage::query()->active()->first();

        $request = $client->requests()->create([
            'description' => 'Limpeza demo — teste de push para o worker '.$workerId,
            'latitude' => $lat,
            'longitude' => $lng,
            'cep' => '49000-000',
            'address' => 'Rua Demo Limpy',
            'address_number' => '100',
            'complement' => 'Apt 1',
            'service_package_id' => $package?->id,
            'package_name' => $package?->name,
            'price' => $package?->price ?? 80.00,
        ]);

        $generateCode->execute($request);

        if ($this->option('sync')) {
            NotifyWorkersOfNewRequest::dispatchSync($request);
            $this->info("Request #{$request->id} created; push sent sync to nearby + forced workers.");
        } else {
            NotifyWorkersOfNewRequest::dispatch($request);
            $this->info("Request #{$request->id} created; NotifyWorkersOfNewRequest queued.");
        }

        $this->line("Client: {$client->id} ({$client->name})");
        $this->line("Near worker: {$workerId} @ {$lat}, {$lng}");
        $this->line('Security code available to the client via API.');

        return self::SUCCESS;
    }
}
