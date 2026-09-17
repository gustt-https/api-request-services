<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentEvent extends Model
{
    protected $table = 'payment_events';
    const UPDATED_AT = null;

    protected $fillable = [
        'provider',
        'provider_event_id',
        'event',
        'payload',
        'created_at'
    ];

    protected $casts = [
        'payload' => 'json'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
