<?php

declare(strict_types = 1);

namespace App\Weather\ScaleConvertor\Strategy;

use App\Weather\Scale;

interface ScaleConvertorStrategy
{
    public function supports(Scale $from, Scale $to): bool;

    public function convert(string $value): string;
}
