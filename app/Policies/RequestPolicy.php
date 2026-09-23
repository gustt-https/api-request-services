<?php

namespace App\Policies;

use App\Enums\RequestStatus;
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
        return $request->workersWasNotified($user)
            && $user->application()->where('request_id', $request->id)->doesntExist()
            && $request->status === RequestStatus::SEARCHING;
    }

    public function finish(User $user, Request $request): bool
    {
        return $request->worker_id === $user->id;
    }

    public function cancel(User $user, Request $request): bool
    {
        return $request->worker_id === $user->id;
    }

    public function cancelByClient(User $user, Request $request)
    {
        return $request->user_id === $user->id
            && in_array($request->status, [
                RequestStatus::ACCEPTED,
                RequestStatus::SEARCHING,
                RequestStatus::AWAIT_PAYMENT
            ], true);
    }

    public function workerLocation(User $user, Request $request)
    {
            return $request->user_id === $user->id;
    }

    public function preview(User $user, Request $request): Response
    {
        if ($request->status !== RequestStatus::SEARCHING) {
            return Response::deny('Esse pedido não está mais disponível.');
        }

        if (! $request->workersWasNotified($user)) {
            return Response::deny('Você não foi notificado sobre este pedido.');
        }

        if ($user->application()->where('request_id', $request->id)->exists()) {
            return Response::deny('Você já respondeu este pedido.');
        }

        return Response::allow();
    }

    public function start(User $user, Request $request)
    {
        return $request->worker_id === $user->id;
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
