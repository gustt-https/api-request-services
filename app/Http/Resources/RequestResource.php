<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'description' => $this->description,
            'worker' => $this->when($this->worker_id !== null, fn() => [
                'id' => $this->worker->id,
                'name' => $this->worker->name
            ]),
            'location' => [
                'latitude' => (string) $this->latitude,
                'longitude' => (string) $this->longitude,
                'address' => $this->address,
                'number' => $this->address_number !== null ? (string) $this->address_number : null,
                'cep' => $this->cep,
                'complement' => $this->complement,
            ],
            'price' => (string) $this->price,
            'timestamps' => $this->lifecycleTimestamps(),
            'security_code' => $this->when(
                isset($this->additional['code']),
                fn() => $this->additional['code'],
            ),

        ];
    }
}
