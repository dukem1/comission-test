<?php

namespace Loaders;

use Contracts\FileLineReader;
use Contracts\TransactionLoader;
use DTO\Transaction;

class FileLoader implements TransactionLoader
{
    public function __construct(
        private readonly string $filePath,
        private readonly FileLineReader $fileReader
    ) {}

    /**
     * @return array<int, Transaction>
     */
    public function load(): array
    {
        $result = [];

        $this->fileReader->open($this->filePath);
        while (!$this->fileReader->eof()) {
            $lineContent = $this->fileReader->fgets();
            if (empty($lineContent)) {
                continue;
            }

            $transaction = Transaction::fromString($lineContent);

            $result[] = $transaction;
        }
        $this->fileReader->close();

        return $result;
    }
}
