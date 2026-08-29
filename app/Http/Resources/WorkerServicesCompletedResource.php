<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerServicesCompletedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->request->id,
            'status' => $this->request->status,
            'description' => $this->request->description,
            'location' => [
                'latitude' => (string) $this->request->latitude,
                'longitude' => (string) $this->request->longitude,
                'address' => $this->request->address,
                'number' => $this->request->address_number !== null ? (string) $this->request->address_number : null,
                'cep' => $this->request->cep,
                'complement' => $this->request->complement,
            ],
            'price' => (string) $this->request->price,
            'timestamps' => [
                'created_at' => $this->created_at,
                'accepted_at' => $this->accepted_at,
                'started_at' => $this->started_at,
                'completed_at' => $this->completed_at,
                'cancelled_at' => $this->cancelled_at
            ]
        ];
    }
}
