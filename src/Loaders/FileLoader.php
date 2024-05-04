<?php

namespace Loaders;

use Contracts\TransactionLoader;
use DTO\Transaction;
use SplFileObject;

class FileLoader implements TransactionLoader
{
    public function __construct(
        private readonly string $filePath,
        private ?SplFileObject $fileReader = null // Mocking of SplFileObject works only this way :(
    ) {}

    /**
     * @return array<int, Transaction>
     */
    public function load(): array
    {
        $result = [];
        if (! $this->fileReader) {
            $this->fileReader = new SplFileObject($this->filePath);
        }
        $file = $this->fileReader;
        while (!$file->eof()) {
            $lineContent = $file->fgets();

            $transaction = Transaction::fromString($lineContent);

            $result[] = $transaction;
        }

        return $result;
    }
}
