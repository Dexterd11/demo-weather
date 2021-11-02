<?php

declare(strict_types=1);

namespace App\Api\Controller;

/**
 *
 */
final class WeatherAverageReport
{
	public function __construct(
		public string $datetime,
		public string $scale,
		public int $value
	)
	{
	}
}
