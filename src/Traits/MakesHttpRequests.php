<?php

namespace Bryceandy\Beem\Traits;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait MakesHttpRequests
{
    /**
     * @param string $url
     * @param string $method
     * @param array $payload
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function call(string $url, string $method = 'POST', array $payload = []): Response
    {
        if (! config('beem.api_key') || ! config('beem.secret_key')) {
            throw new ConfigurationUnavailableException(
                'Your Beem API key and or secret key is not available!'
            );
        }

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' =>
                'Basic ' . base64_encode(config('beem.api_key') . ':' . config('beem.secret_key')),
        ])
            ->{Str::lower($method)}($url, $payload);
    }
}