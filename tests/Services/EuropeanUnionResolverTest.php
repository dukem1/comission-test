<?php

namespace Services;

use PHPUnit\Framework\TestCase;

class EuropeanUnionResolverTest extends TestCase
{
    public function testContains()
    {
        $resolver = new EuropeanUnionResolver();
        $this->assertTrue($resolver->containsCountry('LT'));
    }

    public function testNotContains()
    {
        $resolver = new EuropeanUnionResolver();
        $this->assertFalse($resolver->containsCountry('UA'));
    }
}
