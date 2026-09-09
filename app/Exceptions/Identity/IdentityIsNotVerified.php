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
                IdentityVerificationStatus::PENDING => 'Seus documentos estão em análise. Você fica online automaticamente quando liberarmos — avisamos aqui no app.',
                IdentityVerificationStatus::REJECTED => 'Não conseguimos confirmar sua identidade com as fotos anteriores. Envie novas fotos para liberar seus pedidos.',
                default => 'Confirme sua identidade e você já começa a receber pedidos. São 3 fotos e leva cerca de 2 minutos.',
            },
            status: 403,
        );
    }
}
