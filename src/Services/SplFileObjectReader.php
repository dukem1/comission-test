<?php

namespace Services;

use Contracts\FileLineReader;
use SplFileObject;

class SplFileObjectReader implements FileLineReader
{
    private ?SplFileObject $fileObject = null;

    public function open(string $path): void
    {
        $this->fileObject = new SplFileObject($path);
    }

    public function eof(): bool
    {
        if (is_null($this->fileObject)) {
            throw new \LogicException('Open a file before using it.');
        }
        return $this->fileObject->eof();
    }

    public function fgets(): string
    {
        if (is_null($this->fileObject)) {
            throw new \LogicException('Open a file before using it.');
        }
        return $this->fileObject->fgets();
    }

    public function close(): void
    {
        $this->fileObject = null;
    }
}
