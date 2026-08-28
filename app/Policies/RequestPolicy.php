<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Request $request): bool
    {

        return $request->user_id === $user->id || $request->worker_id === $user->id;
    }

    public function accept(User $user, Request $request): bool
    {
        return $request->worker_id === $user->id;
    }

    public function finish(User $user, Request $request): bool
    {
        return $request->worker_id === $user->id;
    }

    public function cancel(User $user, Request $request): bool
    {
        return $request->worker_id === $user->id;
    }

    public function preview(User $user, Request $request): bool
    {
        return $request->workersWasNotified($user) && $request->status === 'searching';
    }



    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Request $request): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Request $request): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Request $request): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Request $request): bool
    {
        return false;
    }
}
