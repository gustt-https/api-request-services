<?php

namespace App\Service\V1\Requests;

use App\Models\Request;
use Illuminate\Support\Facades\Cache;

class ResolveSearchRadiusService
{
    const DEFAULT_RADIUS = 5;
    const CACHE_KEY = 'request:';
    const TTL = 300;


    public function resolve(Request $request): int
    {
        $key = self::CACHE_KEY . $request->id;

        if (
            !Cache::has($key)
        ) {
            Cache::put($key, 8, self::TTL);
            return self::DEFAULT_RADIUS;
        }

        return Cache::get($key);
    }

    public function forget(Request $request): void
    {
        $key = self::CACHE_KEY . $request->id;
        Cache::forget($key);
    }
}
