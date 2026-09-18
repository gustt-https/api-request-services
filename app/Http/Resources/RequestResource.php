<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $code = $this->plaintextSecurityCodeFor($request->user());

        return [
            'id' => $this->id,
            'status' => $this->status,
            'description' => $this->description,
            'client' => $this->when(
                $request->user()?->id === $this->worker_id,
                fn() => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ],
            ),
            'worker' => $this->when($this->worker_id !== null, fn() => [
                'id' => $this->worker->id,
                'name' => $this->worker->name,
                'avatar_url' => $this->avatarUrl()
            ]),
            'location' => [
                'latitude' => (string) $this->latitude,
                'longitude' => (string) $this->longitude,
                'address' => $this->address,
                'number' => $this->address_number !== null ? (string) $this->address_number : null,
                'cep' => $this->cep,
                'complement' => $this->complement,
            ],
            'photos' => $this->whenLoaded(
                'medias',
                fn () => $this->medias
                    ->map(fn ($media) => [
                        'id' => $media->id,
                        'url' => $media->temporaryUrl(),
                    ])
                    ->values()
                    ->all(),
            ),
            'price' => (string) $this->price,
            'package' => $this->when(
                $this->service_package_id !== null || $this->package_name !== null,
                fn () => [
                    'id' => $this->service_package_id,
                    'name' => $this->package_name ?? $this->servicePackage?->name,
                    'includes' => $this->relationLoaded('servicePackage')
                        ? ($this->servicePackage?->includes ?? [])
                        : [],
                ],
            ),
            'payment' => $this->when(
                $this->relationLoaded('payment') && $request->user()?->id === $this->user_id,
                fn () => $this->payment
                    ? new PaymentResource($this->payment)
                    : null,
            ),
            'timestamps' => $this->lifecycleTimestamps(),
            'security_code' => $this->when($code !== null, $code),
        ];
    }

    private function avatarUrl()
    {
        $path = $this->worker?->workerProfile?->profile_photo;

        if (!$path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
