<?php

namespace Helpers;

use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    public function testRoundUp()
    {
        $testNumber = 1.654321;
        $this->assertEquals(1.654321, Math::amountRoundUp($testNumber, 6));
        $this->assertEquals(1.65433, Math::amountRoundUp($testNumber, 5));
        $this->assertEquals(1.6544, Math::amountRoundUp($testNumber, 4));
        $this->assertEquals(1.655, Math::amountRoundUp($testNumber, 3));
        $this->assertEquals(1.66, Math::amountRoundUp($testNumber, 2));
        $this->assertEquals(1.66, Math::amountRoundUp($testNumber, 1));
        $this->assertEquals(1.66, Math::amountRoundUp($testNumber, 0));

        $this->assertEquals(1.61, Math::amountRoundUp(1.61, 0));
        $this->assertEquals(1.61, Math::amountRoundUp(1.61, 2));
        $this->assertEquals(1.61, Math::amountRoundUp(1.61, 3));
    }
}
