<?php

namespace Loaders;

use DTO\Transaction;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Services\SplFileObjectReader;

class FileLoaderTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testFileLoader(): void
    {
        $fileReaderMock = $this->createMock(SplFileObjectReader::class);
        $fileReaderMock
            ->expects($this->any())
            ->method('fgets')
            ->willReturn('{"bin":"516793","amount":"50.00","currency":"EUR"}');
        $fileReaderMock
            ->expects($this->exactly(2))
            ->method('eof')
            ->willReturn(false, true);

        $fileLoader = new FileLoader('random_file.txt', $fileReaderMock);
        $transactions = $fileLoader->load();

        $this->assertCount(1, $transactions);

        $transaction = $transactions[0];
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals(516793, $transaction->bin);
        $this->assertEquals(50.00, $transaction->amount);
        $this->assertEquals('EUR', $transaction->currency);
    }
}
