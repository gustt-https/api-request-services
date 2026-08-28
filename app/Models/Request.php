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

    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

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
        if (
            !$this->worker_id
        ) {
            return null;
        }

        return $this->applications()
            ->where('request_id', $this->id)
            ->whereNull('cancelled_at')
            ->latest('id')
            ->first();
    }

    public function workersWasNotified(User $user): bool
    {
        return $this->notifications()
            ->where('worker_id', $user->id)
            ->exists();
    }
}
