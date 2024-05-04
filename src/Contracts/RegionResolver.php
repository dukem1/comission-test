<?php

namespace Contracts;

interface RegionResolver
{
    public function containsCountry(string $countryCode): bool;
}
