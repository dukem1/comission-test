<?php

namespace Services;

use Contracts\BinDataService;
use Contracts\HttpClient;
use DTO\BinData;

class BinListService implements BinDataService
{
    private string $serviceUrl;

    public function __construct(
        private readonly HttpClient $httpClient
    ) {
        $this->serviceUrl = $_ENV['BINLIST_URL'] ?? '';
        if (empty($this->serviceUrl)) {
            throw new \RuntimeException('Missing BinList configuration URL');
        }
    }

    public function getData(int $bin): BinData
    {
        $jsonData = $this->httpClient->getJsonAsArray($this->serviceUrl . '/' . $bin);
        if (empty($jsonData)) {
            throw new \InvalidArgumentException('Bin data not found for bin: ' . $bin);
        }

        $binData = new BinData();
        $binData->bin = $bin;
        $binData->scheme = $jsonData['scheme'] ?? '';
        $binData->type = $jsonData['type'] ?? '';
        $binData->brand = $jsonData['brand'] ?? '';
        $binData->countryCode = $jsonData['country']['alpha2'] ?? '';
        $binData->countryName = $jsonData['country']['name'] ?? '';
        $binData->countryCurrency = $jsonData['country']['currency'] ?? '';
        $binData->bankName = $jsonData['bank']['name'] ?? '';

        return $binData;
    }
}
