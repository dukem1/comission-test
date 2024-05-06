<?php

namespace Helpers;

class Math
{
    public static function amountRoundUp($number, $precision = 2)
    {
        if ($precision < 2) {
            $precision = 2;
        }
        $fig = pow(10, $precision);
        return (ceil($number * $fig) / $fig);
    }
}
