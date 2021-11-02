<?php

declare(strict_types=1);

namespace App\Weather;

final class Temperature
{
	public function __construct(
		public Scale $scale,
		public string $value
	)
	{
	}
}
