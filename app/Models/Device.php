<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';
    protected $fillable = [
        'device_id',
        'user_id',
        'token',
        'plataform',
        'is_active',
        'last_used_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
