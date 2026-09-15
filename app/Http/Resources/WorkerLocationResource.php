<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $profile = $this?->worker?->workerProfile;
        return [
            'latitude' => $profile->latitude,
            'longitude' => $profile->longitude,
            'updated_at' => $profile->last_location_at
        ];
    }
}
