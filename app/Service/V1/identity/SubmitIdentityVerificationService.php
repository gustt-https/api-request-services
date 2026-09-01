<?php

namespace App\Service\V1\identity;

use App\Enums\IdentityVerificationStatus;
use App\Exceptions\IdentityAlreadyApprovedException;
use App\Exceptions\IdentityVerificationPendingException;
use App\Http\Requests\WorkerIdentityVerificationRequest;
use App\Http\Resources\SubmitIdentityVerificationResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SubmitIdentityVerificationService
{
    public function execute(User $user, WorkerIdentityVerificationRequest $request): ?JsonResource
    {
        if (
            // Feito troca para enuns
            $user->identityVerification?->status === IdentityVerificationStatus::PENDING
        ) {
            throw new IdentityVerificationPendingException();
        }

        if (
            // Feito troca para enuns
            $user->identityVerification?->status === IdentityVerificationStatus::APPROVED
        ) {
            throw new IdentityAlreadyApprovedException();
        }

        $documentFrontPath = Storage::put('documents', $request->file('document_front'));
        $documentVersePath = Storage::put('documents', $request->file('document_verse'));
        $selfiePath = Storage::put('documents', $request->file('selfie'));

        // Para mvp manter assim, futuramente tabela de submissoes: By: Gustavo.
        $identity = $user->identityVerification()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'document_type' => $request->input('document_type'),
                'document_number' => $request->input('document_number'),
                'document_front_photo_path' => $documentFrontPath,
                'document_verse_photo_path' => $documentVersePath,
                'selfie_photo_path' => $selfiePath,
                'status' => IdentityVerificationStatus::PENDING
            ]
        );

        return new SubmitIdentityVerificationResource($identity);
    }
}
