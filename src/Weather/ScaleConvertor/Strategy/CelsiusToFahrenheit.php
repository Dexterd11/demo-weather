<?php

declare(strict_types = 1);

namespace App\Weather\ScaleConvertor\Strategy;

use App\Weather\Scale;

final class CelsiusToFahrenheit implements ScaleConvertorStrategy
{
    public function supports(Scale $from, Scale $to): bool
    {
        return $from->equals(Scale::celsius()) && $to->equals(Scale::fahrenheit());
    }

    public function convert(string $value): string
    {
        return \bcadd(\bcmul($value, '1.8', 1), '32', 1);
    }
}