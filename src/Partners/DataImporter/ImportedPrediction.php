<?php

declare(strict_types=1);

namespace App\Partners\DataImporter;

use App\Weather\Temperature;

final class ImportedPrediction
{
	public function __construct(
		public string $receiver,
		public string $city,
		public \DateTimeImmutable $date,
		public Temperature $prediction
	)
	{
	}
}
