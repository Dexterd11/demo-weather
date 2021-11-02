<?php

declare(strict_types=1);

namespace App\Partners\DataImporter;

use App\Partners\DataReceiver\PartnerDataReceiver;
use App\Partners\DataReceiver\TemperaturePrediction;
use App\Weather\Scale;
use App\Weather\ScaleConvertor\ScaleConvertor;

final class PartnerDataImporter
{
	/**
	 * @var PartnerDataReceiver[]
	 */
	private $receivers;

	/**
	 * @var ScaleConvertor
	 */
	private $scaleConvertor;

	public function __construct(array $receivers, ScaleConvertor $scaleConvertor)
	{
		$this->receivers = $receivers;
		$this->scaleConvertor = $scaleConvertor;
	}

	/**
	 * @return ImportedPrediction[]
	 */
	public function process(Scale $inScale): \Iterator
	{
		foreach ($this->receivers as $receiver) {
			/** @var TemperaturePrediction $entry */
			foreach ($receiver->obtain() as $entry) {
				$temperature = $entry->temperature->scale->equals($inScale) === false
					? $this->scaleConvertor->convert($entry->temperature, $inScale)
					: $entry->temperature;

				yield new ImportedPrediction(
					receiver: $receiver->name(),
					city: $entry->city,
					date: $entry->date,
					prediction: $temperature
				);
			}
		}
	}
}
