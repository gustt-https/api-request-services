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
            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'address' => $this->address,
                // Ajustado: a coluna é address_number, não number.
                'number' => $this->address_number,
                'complement' => $this->complement
            ],
            'price' => $this->price,
            'timestamps' => [
                "created_at" => $this->created_at,
                "accepted_at" => $this->accepted_at,
                "started_at" => $this->started_at,
                "completed_at" => $this->completed_at,
                "cancelled_at" => $this->cancelled_at
            ]
        ];
    }
}
