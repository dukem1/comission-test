<?php

namespace Services;

use Contracts\HttpClient;
use DTO\BinData;
use PHPUnit\Framework\TestCase;

class BinListServiceTest extends TestCase
{
    protected function setUp(): void
    {
        $_ENV['BINLIST_URL'] = 'https://example.com/';

        parent::setUp();
    }

    public function testGetData()
    {
        $httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $httpClientMock->expects($this->once())
            ->method('getJsonAsArray')
            ->willReturn([
                'number' => [],
                'scheme' => 'visa',
                'brand' => 'Visa Classic',
                'country' => [
                    'numeric' => '208',
                    'alpha2' => 'DK',
                    'name' => 'Denmark',
                    'emoji' => '',
                    'currency' => 'DKK',
                    'latitude' => 56,
                    'longitude' => 10,
                ],
                'bank' => [
                    'name' => 'Jyske Bank A/S',
                ],
            ]);


        $binListService = new BinListService(httpClient: $httpClientMock);
        $binData = $binListService->getData(45717360);

        $this->assertInstanceOf(BinData::class, $binData);
        $this->assertEquals(45717360, $binData->bin);
        $this->assertEquals('DK', $binData->countryCode);
    }

    public function testNoUrlInEnvironmentException()
    {
        unset($_ENV['BINLIST_URL']);

        $httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(\RuntimeException::class);

        new BinListService(httpClient: $httpClientMock);
    }

    public function testNoDataForBinException()
    {
        $httpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $httpClientMock->expects($this->once())
            ->method('getJsonAsArray')
            ->willReturn([]);

        $this->expectException(\InvalidArgumentException::class);

        $binListService = new BinListService(httpClient: $httpClientMock);
        $binListService->getData(45717360);
    }
}
