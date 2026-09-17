<?php

namespace App\Service\V1\requests;

use App\DTOs\Payments\CreatePaymentData;
use App\DTOs\Payments\PaymentData;
use App\Events\RequestCreated;
use App\Exceptions\Requests\ActiveServiceAlreadyExists;
use App\Http\Resources\RequestResource;
use App\Jobs\NotifyClientRequestExpired;
use App\Jobs\NotifyWorkersOfNewRequest;
use App\Models\Request;
use App\Models\User;
use App\Service\V1\firebase\FirebaseService;
use App\Service\V1\Payments\PaymentService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\UploadedFile;

class RequestService
{
    public function makeRequest(User $user, array $payload): JsonResource
    {
        if (
            $user->hasActiveService()
        ) {
            throw new ActiveServiceAlreadyExists();
        }

        $medias = $payload['photos'] ?? [];
        unset($payload['photos']);

        $request = $user->requests()->create($payload);
        $this->addMedia($request, $medias);
        $this->generateSecurityCode($request);

        // TEMP: generate PIX on create so GET .../payment can be validated.
        // Replace later with the real billing moment + error handling.
        app(PaymentService::class)->createForRequest($request);

        // TEMP: do not notify workers while awaiting payment.
        return new RequestResource($request->fresh()->load(['securityCode', 'medias', 'payment']));
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
