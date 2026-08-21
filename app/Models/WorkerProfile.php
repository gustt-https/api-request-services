<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerProfile extends Model
{
    protected $table = 'worker_profiles';

    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }

    public function scopeWithRadius(
        $query,
        float $latitude,
        float $longitude,
        int $radiusKm = 5
    ) {
        $distance = "(6371 * acos(
        cos(radians(?))
        * cos(radians(latitude))
        * cos(radians(longitude) - radians(?))
        + sin(radians(?))
        * sin(radians(latitude))
    ))";

        return $query
            ->selectRaw("*, {$distance} AS distance", [
                $latitude,
                $longitude,
                $latitude,
            ])
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
