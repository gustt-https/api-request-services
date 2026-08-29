<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestApplication extends Model
{
    protected $table = 'request_applications';

    protected $fillable = [
        'request_id',
        'worker_id',
        'accepted_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
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

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }
}
