<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestApplication extends Model
{
    protected $table = 'request_applications';

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }
}
