<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $identity = $this->workerProfile?->identityVerification;
        $worker = $this->workerProfile;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'avatar_url' => $this->avatarUrl(),
            'worker' => $worker ? [
                'available' => (bool) $worker->available,
                'profile_status' => $worker->profile_status,
                'identity' => $identity ? [
                    'status' => $identity->status?->value ?? $identity->status,
                    'rejection_reason' => $identity->rejection_reason,
                ] : null,
            ] : null,
        ];
    }

    /** Selfie URL when identity is approved and the file exists on disk. */
    private function avatarUrl(): ?string
    {
        $identity = $this->workerProfile?->identityVerification;

        if ($identity?->status?->value !== 'approved') {
            return null;
        }

        $path = $identity->selfie_path ?? $identity->selfie_photo_path ?? null;

        if (!$path || !Storage::disk('local')->exists($path)) {
            return null;
        }

        return Storage::disk('local')->temporaryUrl($path, now()->addHours(6));
    }
}
