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

    public function get(string $uri, array $query = [])
    {
        return $this->http()
            ->get($uri, $query)
            ->throw()
            ->json();
    }

    public function delete(string $uri, array $data = [])
    {
        return $this
            ->http()
            ->delete($uri, $data)
            ->throw()
            ->json();
    }
}
