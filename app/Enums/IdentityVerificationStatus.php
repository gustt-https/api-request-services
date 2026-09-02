<?php

namespace App\Enums;

enum IdentityVerificationStatus: string
{
    case UNVERIFIED = 'unverified';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
