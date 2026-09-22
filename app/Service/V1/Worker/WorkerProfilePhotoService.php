<?php

namespace App\Service\V1\Worker;

use App\Exceptions\Profile\WorkerProfileNotFound;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class WorkerProfilePhotoService
{
    public function execute(User $user, UploadedFile $file): string
    {
        $profile = $user->workerProfile;

        if (
            !$profile
        ) {
            throw new WorkerProfileNotFound('Perfil de profissional não encontrado.');
        }

        if (
            $profile->profile_photo
        ) {
            Storage::disk('public')->delete($profile->profile_photo);
        }

        $path = Storage::disk('public')->putFile('profile_photos', $file);

        if (!$path) {
            throw new \RuntimeException('Não foi possível salvar a foto de perfil.');
        }

        $profile->profile_photo = $path;
        $profile->save();
        $profile->refresh();

        return Storage::disk('public')->url($profile->profile_photo);
    }
}
