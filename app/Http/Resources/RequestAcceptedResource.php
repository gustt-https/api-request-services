<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'worker' => [
                'id' => $this->worker->id,
                'name' => $this->worker->name,
                'avatar_url' => $this->avatarUrl()

            ],
            'client' => [
                'id' => $this->user->id,
                'name' => $this->user->name
            ],
            'service' => [
                'description' => $this->description
            ],
            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'address' => $this->address
            ],
            'value' => $this->price,
            'accepted_at' => $this->activeApplication()?->accepted_at,
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
