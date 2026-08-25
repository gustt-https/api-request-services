<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestApplication extends Model
{
    protected $table = 'request_applications';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
