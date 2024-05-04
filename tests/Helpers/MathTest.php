<?php

namespace Helpers;

use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    public function testRoundUp()
    {
        $testNumber = 1.654321;
        $this->assertEquals(1.654321, Math::roundUp($testNumber, 6));
        $this->assertEquals(1.65433, Math::roundUp($testNumber, 5));
        $this->assertEquals(1.6544, Math::roundUp($testNumber, 4));
        $this->assertEquals(1.655, Math::roundUp($testNumber, 3));
        $this->assertEquals(1.66, Math::roundUp($testNumber, 2));
        $this->assertEquals(1.7, Math::roundUp($testNumber, 1));
        $this->assertEquals(2, Math::roundUp($testNumber, 0));
    }
}
