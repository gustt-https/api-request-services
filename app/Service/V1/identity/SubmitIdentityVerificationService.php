<?php

namespace App\Service\V1\identity;

use App\Enums\IdentityVerificationStatus;
use App\Exceptions\IdentityAlreadyApprovedException;
use App\Exceptions\IdentityVerificationPendingException;
use App\Http\Requests\WorkerIdentityVerificationRequest;
use App\Http\Resources\SubmitIdentityVerificationResource;
use App\Models\User;
use App\Models\WorkerIdentityVerification;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubmitIdentityVerificationService
{
    public function execute(User $user, WorkerIdentityVerificationRequest $request): JsonResource
    {
        $profile = $user->workerProfile;

        if (!$profile) {
            throw new HttpException(403, 'Perfil de profissional não encontrado.');
        }

        $existing = $profile->identityVerification;

        if ($existing?->status === IdentityVerificationStatus::PENDING) {
            throw new IdentityVerificationPendingException();
        }

        if ($existing?->status === IdentityVerificationStatus::APPROVED) {
            throw new IdentityAlreadyApprovedException();
        }

        $documentFrontPath = Storage::disk('local')->put('documents', $request->file('document_front'));
        $documentVersePath = Storage::disk('local')->put('documents', $request->file('document_verse'));
        $selfiePath = Storage::disk('local')->put('documents', $request->file('selfie'));

        $identity = WorkerIdentityVerification::query()->updateOrCreate(
            ['worker_profile_id' => $profile->id],
            [
                'document_type' => $request->input('document_type'),
                'document_number' => $request->input('document_number'),
                'front_path' => $documentFrontPath,
                'back_path' => $documentVersePath,
                'selfie_path' => $selfiePath,
                'status' => IdentityVerificationStatus::PENDING,
                'submitted_at' => now(),
                'rejection_reason' => null,
                'reviewed_at' => null,
            ]
        );

        return new SubmitIdentityVerificationResource($identity);
    }
}
