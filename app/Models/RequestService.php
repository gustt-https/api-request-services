<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestService extends Model
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

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}
