<?php

namespace DTO;

class Transaction
{
    public int $bin;
    public float $amount;
    public string $currency;

    public static function fromString(string $data): self
    {
        $transactionData = json_decode($data, true);
        $transaction = new Transaction();
        $transaction->bin = $transactionData['bin'];
        $transaction->amount = $transactionData['amount'];
        $transaction->currency = $transactionData['currency'];

        return $transaction;
    }
}
