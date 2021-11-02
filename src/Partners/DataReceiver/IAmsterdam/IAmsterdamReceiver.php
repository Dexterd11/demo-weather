<?php

declare(strict_types=1);

namespace App\Partners\DataReceiver\IAmsterdam;

use App\Partners\DataReceiver\PartnerDataReceiver;
use App\Partners\DataReceiver\TemperaturePrediction;
use App\Weather\Scale;
use App\Weather\Temperature;
use Symfony\Component\DomCrawler\Crawler;
use function App\Partners\DataReceiver\checkFilePath;
use function App\Partners\DataReceiver\datetimeInstantiator;

final class IAmsterdamReceiver implements PartnerDataReceiver
{
	/**
	 * @var Crawler
	 */
	private $crawler;

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
		return 'iamsterdam';
	}

	public function obtain(): \Iterator
	{
		$city = $this->crawler->filterXPath('//predictions/city')->first()->text();
		$date = $this->crawler->filterXPath('//predictions/date')->first()->text();

		if (!empty($city) && !empty($date)) {
			foreach ($this->crawler->filterXPath('predictions//prediction') as $node) {
				[$time, $value] = array_values(
					array_filter(
						array_map(
							static function ($q): ?string {
								$value = trim($q->nodeValue);

								return $value !== '' ? $value : null;
							},
							iterator_to_array($node->childNodes->getIterator())
						)
					)
				);

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

	private function __construct(string $stabFilePath)
	{
		$this->crawler = new Crawler(
			file_get_contents($stabFilePath)
		);
	}
}
