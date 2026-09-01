<?php

namespace App\Enums;


enum IdentityVerificationStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}