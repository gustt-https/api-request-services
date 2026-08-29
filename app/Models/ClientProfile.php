<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientProfile extends Model
{

    protected $table = 'client_profiles';

    protected $fillable = [
        'default_state',
        'default_city',
        'default_neighborhood',
        'default_complement',
        'default_address_number',
        'default_address',
        'default_cep',
        'phone_confirmed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
