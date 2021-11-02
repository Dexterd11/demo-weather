<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Weather\Scale;
use App\Weather\ScaleConvertor\ScaleConvertor;
use App\Weather\Temperature;
use Doctrine\ORM\EntityManagerInterface;

final class WeatherAveragePresenter
{
	public function __construct(
		private EntityManagerInterface $em,
		private ScaleConvertor $scaleConvertor
	)
	{

	}

	public function averageReport(
		string $city,
		\DateTimeImmutable $from,
		\DateTimeImmutable $to,
		Scale $inScale
	): array
	{
		$collection = [];
		$entries = $this->loadData($city, $from, $to);

		foreach ($entries as $entry) {
			$scale = Scale::fromString($entry['scale']);
			$value = $inScale->equals($scale)
				? $entry['average']
				: $this->scaleConvertor->convert(new Temperature($scale, (string)$entry['average']), $inScale)->value;

			$collection[] = new WeatherAverageReport(
				$entry['datetime'],
				$inScale->toString(),
				(int)$value
			);
		}

		return $collection;
	}

	private function loadData(
		string $city,
		\DateTimeImmutable $from,
		\DateTimeImmutable $to
	): array
	{
		return $this->em
			->getConnection()
			->createQueryBuilder()
			->select('round(avg(wp.value), 2) as average, wp.scale, wp.datetime')
			->from('weather_prediction', 'wp')
			->groupBy('wp.datetime, wp.scale')
			->orderBy('wp.datetime', 'ASC')
			->where('wp.city = ?')
			->andWhere('wp.datetime BETWEEN ? AND ?')
			->setParameters([$city, $from->format('c'), $to->format('c')])
			->executeQuery()
			->fetchAllAssociative();
	}
}
