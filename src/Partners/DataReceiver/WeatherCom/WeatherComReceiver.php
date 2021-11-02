<?php

declare(strict_types = 1);

namespace App\Partners\DataReceiver\WeatherCom;

use App\Infrastructure\CsvReader;
use App\Infrastructure\SimpleCsvFileReader;
use App\Partners\DataReceiver\Criteria;
use App\Partners\DataReceiver\PartnerDataReceiver;
use App\Partners\DataReceiver\TemperaturePrediction;
use App\Weather\Scale;
use App\Weather\Temperature;
use function App\Partners\DataReceiver\checkFilePath;
use function App\Partners\DataReceiver\datetimeInstantiator;

final class WeatherComReceiver implements PartnerDataReceiver
{
    /**
     * @var string
     */
    private $stabFilePath;

    /**
     * @var CsvReader
     */
    private $csvReader;

    /**
     * @throws \InvalidArgumentException
     */
    public static function create(string $stabFilePath, CsvReader $withReader = null): self
    {
        checkFilePath($stabFilePath);

        return new self($stabFilePath, $withReader);
    }

    public function name(): string
    {
        return 'weather.com';
    }

    public function obtain(): \Iterator
    {
        foreach($this->csvReader->read($this->stabFilePath) as $line)
        {
            $isFulfilled = count(array_filter($line)) === 5;

            if($isFulfilled === true)
            {
                [, $city, $date, $time, $value] = $line;

                yield new TemperaturePrediction(
                    city: $city,
                    date: datetimeInstantiator(
                        datetimeString: sprintf('%s %s', $date, $time),
                        withFormat: 'Ymd H:i',
                        timezone: 'Europe/Amsterdam'
                    ),
                    temperature: new Temperature(
                        scale: Scale::celsius(),
                        value: $value
                    )
                );
            }
        }
    }

    private function __construct(string $stabFilePath, ?CsvReader $withReader)
    {
        $this->stabFilePath = $stabFilePath;
        $this->csvReader    = $withReader ?? new SimpleCsvFileReader();
    }
}
