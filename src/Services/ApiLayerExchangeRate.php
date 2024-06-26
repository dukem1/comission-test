<?php

namespace Services;

use Contracts\ExchangeRateService;
use Contracts\HttpClient;

class ApiLayerExchangeRate implements ExchangeRateService
{
    private string $serviceUrl;
    private string $apiKey;
    private string $baseCurrency;
    private array $cache = [];

    public function __construct(
        private readonly HttpClient $httpClient
    ) {
        $this->serviceUrl = $_ENV['APILAYER_RATES_URL'] ?? '';
        if (empty($this->serviceUrl)) {
            throw new \RuntimeException('Missing ApiLayer configuration URL');
        }
        $this->apiKey = $_ENV['APILAYER_KEY'] ?? '';
        if (empty($this->apiKey)) {
            throw new \RuntimeException('Missing ApiLayer access key');
        }
        $this->baseCurrency = $_ENV['BASE_CURRENCY'] ?? 'EUR';
    }

    public function getRate(string $currencyCode): float
    {
        if ($currencyCode === $this->baseCurrency) {
            return 1;
        }

        if (! isset($this->cache[$currencyCode])) {
            $jsonData = $this->httpClient->getJsonAsArray(
                url: $this->serviceUrl . '/?symbols=' . $currencyCode . '&base=' . $this->baseCurrency,
                headers: [
                    'Content-Type: text/plain',
                    'apikey: ' . $this->apiKey,
                ]
            );
            if (empty($jsonData) || !isset($jsonData['rates'][$currencyCode])) {
                throw new \InvalidArgumentException('Rate data not found for currency: ' . $currencyCode);
            }
            $this->cache[$currencyCode] = $jsonData['rates'][$currencyCode];
        }

        return $this->cache[$currencyCode];
    }
}
