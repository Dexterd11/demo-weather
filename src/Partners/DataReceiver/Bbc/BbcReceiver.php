<?php

declare(strict_types=1);

namespace App\Partners\DataReceiver\Bbc;

use App\Partners\DataReceiver\PartnerDataReceiver;
use App\Partners\DataReceiver\TemperaturePrediction;
use App\Weather\Scale;
use App\Weather\Temperature;
use function App\Partners\DataReceiver\checkFilePath;
use function App\Partners\DataReceiver\datetimeInstantiator;

final class BbcReceiver implements PartnerDataReceiver
{
	/**
	 * @throws \InvalidArgumentException
	 */
	public static function create(string $stabFilePath): self
	{
		checkFilePath($stabFilePath);

		return new self($stabFilePath);
	}

	public function name(): string
	{
		return 'bbc';
	}

	public function obtain(): \Iterator
	{
		$content = $this->readContent();

		foreach ($content['prediction'] as $prediction) {
			$isFulfilled = count(array_filter($prediction)) === 2;

			if ($isFulfilled === true) {
				yield new TemperaturePrediction(
					city: $content['city'],
					date: datetimeInstantiator(
					datetimeString: \sprintf('%s %s', $content['date'], $prediction['time']),
					withFormat: 'Ymd H:i',
					timezone: 'Europe/Amsterdam'
				),
					temperature: new Temperature(
						scale: Scale::fahrenheit(),
						value: $prediction['value']
					)
				);
			}
		}
	}

	private function readContent(): array
	{
		$data = json_decode(file_get_contents($this->stabFilePath), true, 512, \JSON_THROW_ON_ERROR);

		if (
			!empty($data['predictions']['prediction']) &&
			!empty($data['predictions']['city']) &&
			!empty($data['predictions']['date'])
		) {
			return $data['predictions'];
		}

		throw new \InvalidArgumentException('Incorrect predictions file format');
	}

	private function __construct(
		private string $stabFilePath
	)
	{
	}
}
