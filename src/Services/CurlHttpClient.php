<?php

namespace Services;

use Contracts\HttpClient;

class CurlHttpClient implements HttpClient
{
    public function getJsonAsArray(string $url, array $headers = null): array
    {
        $ch = curl_init();
        if (! is_null($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}
