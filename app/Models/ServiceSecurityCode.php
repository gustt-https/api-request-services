<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSecurityCode extends Model
{
    protected $table = 'service_security_codes';

    protected $fillable = [
        'code',
        'created_at'
    ];

    public function request()
    {
        return $this->belongsTo(RequestService::class);
    }
}
