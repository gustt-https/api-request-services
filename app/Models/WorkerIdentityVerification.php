<?php

namespace App\Models;

use App\Enums\IdentityVerificationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerIdentityVerification extends Model
{
    protected $table = 'worker_identity_verifications';

    protected $fillable = [
        'worker_profile_id',
        'status',
        'document_type',
        'document_number',
        'front_path',
        'back_path',
        'selfie_path',
        'provider',
        'provider_ref',
        'submitted_at',
        'reviewed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'status' => IdentityVerificationStatus::class,
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function workerProfile(): BelongsTo
    {
        return $this->belongsTo(WorkerProfile::class);
    }
}
