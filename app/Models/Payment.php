<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';

    protected $fillable = [
        'request_id',
        'provider',
        'provider_payment_id',
        'external_reference',
        'amount',
        'status',
        'pix_payload',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'status' => PaymentStatus::class,
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function events()
    {
        return $this->hasMany(PaymentEvent::class);
    }
}
