<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function identityVerification()
    {
        return $this->hasOneThrough(
            WorkerIdentityVerification::class,
            WorkerProfile::class,
            'user_id',
            'worker_profile_id',
            'id',
            'id'
        );
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function clientProfile()
    {
        return $this->hasOne(ClientProfile::class);
    }

    public function workerProfile()
    {
        return $this->hasOne(WorkerProfile::class);
    }

    public function ensureWorkerProfile(): WorkerProfile
    {
        return $this->workerProfile()->firstOrCreate([]);
    }

    public function ensureClientProfile(): ClientProfile
    {
        return $this->clientProfile()->firstOrCreate([]);
    }


    public function application()
    {
        return $this->hasMany(RequestApplication::class, 'worker_id');
    }
}
