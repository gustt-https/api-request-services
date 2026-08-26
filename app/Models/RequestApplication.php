<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestApplication extends Model
{
    protected $table = 'request_applications';

    // Ajustado: FK é worker_id, não user_id (belongsTo User padrão quebrava).
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function request()
    {
        return $this->belongsTo(RequestService::class, 'request_id');
    }
}
