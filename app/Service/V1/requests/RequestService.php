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
use Illuminate\Support\Facades\DB;

class RequestService
{
    public function __construct(public PaymentService $payment) {}

    public function makeRequest(User $user, array $payload): JsonResource
    {
        if (
            $user->hasActiveService()
        ) {
            throw new ActiveServiceAlreadyExists();
        }

        $medias = $payload['photos'] ?? [];
        unset($payload['photos']);

        $request =  DB::transaction(function () use ($user, $payload, $medias) {
            $request = $user->requests()->create($payload);

            $this->addMedia($request, $medias);
            $this->generateSecurityCode($request);

            return $request->refresh();
        });


        $this->payment->createForRequest($request);
        return new RequestResource($request->load(['securityCode', 'medias', 'payment']));
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
