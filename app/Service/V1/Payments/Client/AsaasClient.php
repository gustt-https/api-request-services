<?php

namespace App\Service\V1\Payments\Client;

use Illuminate\Support\Facades\Http;

class AsaasClient
{
    private function http()
    {
        return Http::baseUrl(config('asaas.base_url'))
            ->withHeaders([
                'access_token' => config('asaas.api_key'),
                'accept' => 'application/json',
                'content-type' => 'application/json'
            ])->asJson();
    }

    public function post(string $uri, array $data = [])
    {
        return $this->http()
            ->post($uri, $data)
            ->throw()
            ->json();
    }

    public function get(string $uri)
    {
        return $this->http()
            ->get($uri)
            ->throw()
            ->json();
    }
}
