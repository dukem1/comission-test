<?php

namespace Services;

use Contracts\BinDataService;
use Contracts\ExchangeRateService;
use Contracts\RegionResolver;
use DTO\Transaction;

class CommissionCalculator
{
    public function __construct(
        private readonly BinDataService $binDataService,
        private readonly ExchangeRateService $exchangeRateService,
        private readonly RegionResolver $regionResolver,
        private readonly float $regionModifier,
        private readonly float $outsideRegionModifier,
    ) {}

    public function getCommission(Transaction $transaction): float
    {
        $rate = $this->exchangeRateService->getRate($transaction->currency);

        $baseAmount = $transaction->amount * $rate;

        $binData = $this->binDataService->getData($transaction->bin);

        if ($this->regionResolver->containsCountry($binData->countryCode)) {
            return $this->regionModifier * $baseAmount;
        } else {
            return $this->outsideRegionModifier * $baseAmount;
        }
    }
}
