<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestNotification extends Model
{
    protected $table = 'request_notifications';
    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'worker_id',
        'status',
        'notified_at'
    ];

    public function request()
    {
        return  $this->belongsTo(Request::class);
    }
}
