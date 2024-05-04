<?php

namespace Services;

use Contracts\HttpClient;
use PHPUnit\Framework\TestCase;

class ApiLayerExchangeRateTest extends TestCase
{
    protected function setUp(): void
    {
        $_ENV['APILAYER_RATES_URL'] = 'https://example.com/';
        $_ENV['APILAYER_KEY'] = 'randomString';

        parent::setUp();
    }

    public function testGetRate()
    {
        $httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $httpClientMock->expects($this->once())
            ->method('getJsonAsArray')
            ->willReturn([
                'base' => 'EUR',
                'date' => '2024-05-04',
                'rates' => [
                    'USD' => 1.077179,
                ],
                'success' => true,
                'timestamp' => 1714830244,
            ]);


        $apiLayerExchangeRate = new ApiLayerExchangeRate(httpClient: $httpClientMock);
        $rate = $apiLayerExchangeRate->getRate('USD');

        $this->assertIsFloat($rate);
        $this->assertEquals(1.077179, $rate);
    }

    public function testNoUrlInEnvironmentException()
    {
        unset($_ENV['APILAYER_RATES_URL']);

        $httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(\RuntimeException::class);

        new ApiLayerExchangeRate(httpClient: $httpClientMock);
    }

    public function testNoApiKeyInEnvironmentException()
    {
        unset($_ENV['APILAYER_KEY']);

        $httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(\RuntimeException::class);

        new ApiLayerExchangeRate(httpClient: $httpClientMock);
    }

    public function testNoDataForCurrencyCodeException()
    {
        $httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $httpClientMock->expects($this->once())
            ->method('getJsonAsArray')
            ->willReturn([]);

        $this->expectException(\InvalidArgumentException::class);

        $binListService = new ApiLayerExchangeRate(httpClient: $httpClientMock);
        $binListService->getRate('USD');
    }
}
