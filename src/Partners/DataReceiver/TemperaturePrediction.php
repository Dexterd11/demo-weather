<?php

declare(strict_types=1);

namespace App\Partners\DataReceiver;

use App\Weather\Temperature;

final class TemperaturePrediction
{
	public function __construct(
		public string $city,
		public \DateTimeImmutable $date,
		public Temperature $temperature
	)
	{
	}
}
