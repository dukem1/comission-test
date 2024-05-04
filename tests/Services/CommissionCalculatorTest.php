<?php

namespace Services;

use Contracts\HttpClient;
use DTO\Transaction;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    protected function setUp(): void
    {
        $_ENV['BINLIST_URL'] = 'https://example.com/';
        $_ENV['APILAYER_RATES_URL'] = 'https://example.com/';
        $_ENV['APILAYER_KEY'] = 'randomString';

        parent::setUp();
    }
    public function testGetCommission()
    {
        $binListHttpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $binListHttpClientMock->expects($this->once())
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
        $binListService = new BinListService(httpClient: $binListHttpClientMock);

        $apiLayerHttpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $apiLayerHttpClientMock->expects($this->once())
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


        $apiLayerExchangeRate = new ApiLayerExchangeRate(httpClient: $apiLayerHttpClientMock);
        $resolver = new EuropeanUnionResolver();

        $calculator = new CommissionCalculator(
            binDataService: $binListService,
            exchangeRateService: $apiLayerExchangeRate,
            regionResolver: $resolver,
            regionModifier: 0.01,
            outsideRegionModifier: 0.02
        );

        $transaction = new Transaction();
        $transaction->bin = 45717360;
        $transaction->amount = 100;
        $transaction->currency = 'USD';

        $commission = $calculator->getCommission($transaction);

        $this->assertIsFloat($commission);
        $this->assertEquals(1.077179 ,$commission);
    }

    public function testGetCommissionNonEU()
    {
        $binListHttpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $binListHttpClientMock->expects($this->once())
            ->method('getJsonAsArray')
            ->willReturn([
                'number' => [],
                'scheme' => 'visa',
                'brand' => 'Visa Classic',
                'country' => [
                    'numeric' => '208',
                    'alpha2' => 'UA',
                    'name' => 'Ukraine',
                    'emoji' => '',
                    'currency' => 'USD',
                    'latitude' => 56,
                    'longitude' => 10,
                ],
                'bank' => [
                    'name' => 'Jyske Bank A/S',
                ],
            ]);
        $binListService = new BinListService(httpClient: $binListHttpClientMock);

        $apiLayerHttpClientMock = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $apiLayerHttpClientMock->expects($this->once())
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


        $apiLayerExchangeRate = new ApiLayerExchangeRate(httpClient: $apiLayerHttpClientMock);
        $resolver = new EuropeanUnionResolver();

        $calculator = new CommissionCalculator(
            binDataService: $binListService,
            exchangeRateService: $apiLayerExchangeRate,
            regionResolver: $resolver,
            regionModifier: 0.01,
            outsideRegionModifier: 0.02
        );

        $transaction = new Transaction();
        $transaction->bin = 45717360;
        $transaction->amount = 100;
        $transaction->currency = 'USD';

        $commission = $calculator->getCommission($transaction);

        $this->assertIsFloat($commission);
        $this->assertEquals(2.154358 ,$commission);
    }
}
