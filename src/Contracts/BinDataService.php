<?php

namespace Contracts;

use DTO\BinData;

interface BinDataService
{
    public function getData(int $bin): BinData;
}
