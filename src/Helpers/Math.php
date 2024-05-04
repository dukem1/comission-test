<?php

namespace Helpers;

class Math
{
    public static function roundUp($number, $precision = 2)
    {
        $fig = pow(10, $precision);
        return (ceil($number * $fig) / $fig);
    }
}
