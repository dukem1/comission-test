<?php

namespace Contracts;

interface HttpClient
{
    public function getJsonAsArray(string $url, array $headers = null): array;
}
