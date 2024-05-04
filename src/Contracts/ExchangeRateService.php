<?php

namespace Contracts;

use DTO\BinData;

interface ExchangeRateService
{
    public function getRate(string $currencyCode): float;
}
