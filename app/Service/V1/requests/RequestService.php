<?php

namespace App\Service\V1\requests;

use App\Exceptions\Requests\ActiveServiceAlreadyExists;
use App\Http\Resources\RequestResource;
use App\Models\Request;
use App\Models\ServicePackage;
use App\Models\User;
use App\Service\V1\Payments\PaymentService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class RequestService
{

    public function __construct(public PaymentService $paymentService)
    {
    }

    public function makeRequest(User $user, array $payload): JsonResource
    {
        if (
            $user->hasActiveService()
        ) {
            throw new ActiveServiceAlreadyExists();
        }

        $package = ServicePackage::query()
            ->active()
            ->findOrFail($payload['service_package_id']);

        $medias = $payload['photos'] ?? [];
        unset($payload['photos'], $payload['price']);

        $payload['service_package_id'] = $package->id;
        $payload['package_name'] = $package->name;
        $payload['price'] = $package->price;

        $request = DB::transaction(function () use ($user, $payload, $medias) {
            $request = $user->requests()->create($payload);
            $request->payment()->create([
                'provider' => 'asaas',
                'external_reference' => 'request:' . $request->id,
                'amount' => $request->price,
                'status' => 'pending',
            ]);

            $this->addMedia($request, $medias);
            $this->generateSecurityCode($request);
            return $request->refresh();
        });

        $payment = $request->refresh()->payment;
        $this->paymentService->process($payment);

        return new RequestResource($request->load(['securityCode', 'medias', 'payment', 'servicePackage']));
    }

    /**
     * @param  list<UploadedFile>  $medias
     */
    private function addMedia(Request $request, array $medias): void
    {
        foreach ($medias as $media) {
            if (! $media instanceof UploadedFile) {
                continue;
            }

            $path = $media->store("requests/{$request->id}", 'private');

            if (! $path) {
                throw new \RuntimeException('Não foi possível salvar a foto do pedido.');
            }

            $request->medias()->create(['path' => $path]);
        }
    }

    private function generateSecurityCode(Request $request): void
    {
        $code = (string) random_int(100000, 999999);

        $request->securityCode()->create([
            'code' => $code,
        ]);
    }
}
