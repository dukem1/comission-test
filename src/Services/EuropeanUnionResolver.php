<?php

namespace Services;

use Contracts\RegionResolver;

class EuropeanUnionResolver implements RegionResolver
{
    private array $countryCodes = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public function containsCountry(string $countryCode): bool
    {
        return in_array($countryCode, $this->countryCodes);
    }
}
