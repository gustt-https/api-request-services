<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Offer payload for notified workers. Intentionally omits client identity
 * and security code — those appear only after accept / start.
 *
 * Lifecycle dates come from request_applications (null until accept).
 */
class RequestResourcePreview extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'description' => $this->description,
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
                    'name' => $this->package_name,
                    'includes' => $this->relationLoaded('servicePackage') ? ($this->servicePackage?->includes ?? []) : [],
                ],
            ),
            'timestamps' => $this->lifecycleTimestamps(),
        ];
    }
}
