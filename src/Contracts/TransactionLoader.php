<?php

namespace Contracts;

use DTO\Transaction;

interface TransactionLoader
{
    /**
     * @return array<int, Transaction>
     */
    public function load(): array;
}
