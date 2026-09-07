<?php

namespace App\Exceptions\Identity;

use App\Enums\IdentityVerificationStatus;
use App\Exceptions\DomainException;

class IdentityIsNotVerified extends DomainException
{
    public function __construct(?IdentityVerificationStatus $status = null)
    {
        parent::__construct(
            message: match ($status) {
                IdentityVerificationStatus::PENDING => 'Sua verificação de identidade ainda está em análise.',
                IdentityVerificationStatus::REJECTED => 'Sua verificação de identidade foi recusada. Envie os documentos novamente.',
                default => 'Complete a verificação de identidade para ficar disponível.',
            },
            status: 403,
        );
    }
}
