<?php

namespace Contracts;

interface FileLineReader
{
    public function open(string $path): void;
    public function eof(): bool;
    public function fgets(): string;
    public function close(): void;
}
