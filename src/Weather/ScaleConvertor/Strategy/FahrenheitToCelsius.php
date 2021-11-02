<?php

declare(strict_types = 1);

namespace App\Weather\ScaleConvertor\Strategy;

use App\Weather\Scale;

class FahrenheitToCelsius implements ScaleConvertorStrategy
{
    public function supports(Scale $from, Scale $to): bool
    {
        return $from->equals(Scale::fahrenheit()) && $to->equals(Scale::celsius());
    }

    public function convert(string $value): string
    {
        return \bcdiv(\bcsub($value, '32', 2), '1.8', 2);
    }
}
