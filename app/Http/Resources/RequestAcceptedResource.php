<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestAcceptedResource extends JsonResource
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
            'worker_id' => $this->worker_id,
            'client' => [
                'id' => $this->user->id,
                'name' => $this->user->name
            ],
            'service' => [
                'type' => $this->type,
                'description' => $this->description
            ],
            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'address' => $this->address
            ],
            'value' => $this->value,
            'accepted_at' => $this->accepted_at
        ];
    }
}
