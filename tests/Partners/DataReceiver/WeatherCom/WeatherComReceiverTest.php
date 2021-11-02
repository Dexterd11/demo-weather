<?php

declare(strict_types=1);

namespace App\Tests\Partners\DataReceiver\WeatherCom;

use App\Partners\DataReceiver\TemperaturePrediction;
use App\Partners\DataReceiver\WeatherCom\WeatherComReceiver;
use App\Weather\Scale;
use PHPUnit\Framework\TestCase;

final class WeatherComReceiverTest extends TestCase
{
	/**
	 * @var WeatherComReceiver
	 */
	private $receiver;

	public function setUp(): void
	{
		parent::setUp();

		$this->receiver = WeatherComReceiver::create(
			__DIR__ . '/../../../../data-sources/temps.csv'
		);
	}

	/**
	 * @test
	 */
	public function createWithIncorrectStabPath(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage("Unable to read file `abube`");

		WeatherComReceiver::create('abube');
	}

	/**
	 * @test
	 */
	public function createWithEmptyStabPath(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage("Unable to read file ``");

		WeatherComReceiver::create('');
	}

	/**
	 * @test
	 */
	public function obtain(): void
	{
		$result = \iterator_to_array($this->receiver->obtain());

		$this->assertCount(1, $result);

		/** @var TemperaturePrediction $latestResult */
		$latestResult = \end($result);

		$this->assertTrue($latestResult->temperature->scale->equals(Scale::celsius()));
		$this->assertEquals('05', $latestResult->temperature->value);
		$this->assertEquals('Amsterdam', $latestResult->city);
		$this->assertEquals('2018-01-12 00:00', $latestResult->date->format('Y-m-d H:i'));
	}
}
