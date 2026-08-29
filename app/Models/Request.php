<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = 'requests';

    protected $fillable = [
        'description',
        'latitude',
        'longitude',
        'cep',
        'address',
        'address_number',
        'complement',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function securityCode()
    {
        return $this->hasOne(ServiceSecurityCode::class);
    }

    public function applications()
    {
        return $this->hasMany(RequestApplication::class);
    }

    public function notifications()
    {
        return $this->hasMany(RequestNotification::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function activeApplication(): ?RequestApplication
    {
        if (!$this->worker_id) {
            return null;
        }

        return $this->applications()
            ->where('request_id', $this->id)
            ->whereNull('cancelled_at')
            ->latest('id')
            ->first();
    }

    public function completedRequests(User $user)
    {
        return $this->where('worker_id', $user->id)
            ->where('status', 'completed')
            ->get();
    }

    /**
     * Lifecycle dates live on request_applications; request only has created_at.
     *
     * @return array{created_at: mixed, accepted_at: mixed, started_at: mixed, completed_at: mixed, cancelled_at: mixed}
     */
    public function lifecycleTimestamps(): array
    {
        $application = $this->activeApplication();

        return [
            'created_at' => $this->created_at,
            'accepted_at' => $application?->accepted_at,
            'started_at' => $application?->started_at,
            'completed_at' => $application?->completed_at,
            'cancelled_at' => $application?->cancelled_at,
        ];
    }

    public function workersWasNotified(User $user): bool
    {
        return $this->notifications()
            ->where('worker_id', $user->id)
            ->exists();
    }
}
