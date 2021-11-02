<?php

declare(strict_types=1);

namespace App\Tests\Weather\ScaleConvertor;

use App\Weather\Scale;
use App\Weather\ScaleConvertor\ScaleConvertor;
use App\Weather\ScaleConvertor\Strategy\CelsiusToFahrenheit;
use App\Weather\ScaleConvertor\Strategy\FahrenheitToCelsius;
use App\Weather\Temperature;
use PHPUnit\Framework\TestCase;

final class ScaleConvertorTest extends TestCase
{
	/**
	 * @var ScaleConvertor
	 */
	private $convertor;

	protected function setUp(): void
	{
		parent::setUp();

		$this->convertor = new ScaleConvertor([
			new FahrenheitToCelsius(),
			new CelsiusToFahrenheit()
		]);
	}

	/**
	 * @test
	 */
	public function fahrenheitToCelsius(): void
	{
		$temperature = new Temperature(
			scale: Scale::fahrenheit(),
			value: '90'
		);

		$this->assertEquals('32.22', $this->convertor->convert($temperature, Scale::celsius())->value);
	}

	/**
	 * @test
	 */
	public function celsiusToFahrenheit(): void
	{
		$temperature = new Temperature(
			scale: Scale::celsius(),
			value: '50'
		);

		$this->assertEquals('122.0', $this->convertor->convert($temperature, Scale::fahrenheit())->value);
	}
}
