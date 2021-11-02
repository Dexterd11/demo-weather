<?php

declare(strict_types = 1);

namespace App\Weather\ScaleConvertor;

use App\Weather\Scale;
use App\Weather\ScaleConvertor\Strategy\ScaleConvertorStrategy;
use App\Weather\Temperature;

final class ScaleConvertor
{
    /**
     * @var ScaleConvertorStrategy[]
     */
    private $strategies;

    /**
     * @param ScaleConvertorStrategy[] $strategies
     */
    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    public function convert(Temperature $temperature, Scale $to): Temperature
    {
        foreach($this->strategies as $scaleConvertorStrategy)
        {
            if($scaleConvertorStrategy->supports($temperature->scale, $to))
            {
                return new Temperature(
                    scale: $to,
                    value: $scaleConvertorStrategy->convert($temperature->value)
                );
            }
        }

        throw new \RuntimeException('Unable to find conversion strategy');
    }
}
