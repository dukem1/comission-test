<?php
require './vendor/autoload.php';

use Services\CommissionCalculator;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

if (! file_exists($argv[1]) || is_dir($argv[1])) {
    throw new InvalidArgumentException('File not found');
}

$fileReader = new \Services\SplFileObjectReader();
$transactionLoader = new \Loaders\FileLoader(
    filePath: $argv[1],
    fileReader: $fileReader
);
$transactions = $transactionLoader->load();

$httpClient = new \Services\CurlHttpClient();
$binDataService = new \Services\BinListService(httpClient: $httpClient);
$exchangeRateService = new \Services\ExchangeRatesApi(httpClient: $httpClient);
$regionResolver = new \Services\EuropeanUnionResolver();
$commissionCalculator = new CommissionCalculator(
    binDataService: $binDataService,
    exchangeRateService: $exchangeRateService,
    regionResolver: $regionResolver,
    regionModifier: 0.01,
    outsideRegionModifier: 0.02
);

foreach ($transactions as $transaction) {
    $commission = $commissionCalculator->getCommission($transaction);
    print $commission . PHP_EOL;
}
