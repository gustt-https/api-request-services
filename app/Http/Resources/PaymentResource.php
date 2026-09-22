<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status?->value ?? $this->status,
            'amount' => (string) $this->amount,
            'pix_payload' => $this->pix_payload,
            'paid_at' => $this->paid_at?->toIso8601String(),
        ];
    }
}
