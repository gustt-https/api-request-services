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


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
