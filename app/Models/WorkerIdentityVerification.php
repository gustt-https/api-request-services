<?php

namespace App\Models;

use App\Enums\IdentityVerificationStatus;
use Illuminate\Database\Eloquent\Model;

class WorkerIdentityVerification extends Model
{
    protected $table = 'worker_identity_verifications';


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'status' => IdentityVerificationStatus::class
    ];
}
