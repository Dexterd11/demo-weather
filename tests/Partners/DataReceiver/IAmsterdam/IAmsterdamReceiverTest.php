<?php

declare(strict_types=1);

namespace App\Tests\Partners\DataReceiver\IAmsterdam;

use App\Partners\DataReceiver\IAmsterdam\IAmsterdamReceiver;
use App\Partners\DataReceiver\TemperaturePrediction;
use App\Weather\Scale;
use PHPUnit\Framework\TestCase;

final class IAmsterdamReceiverTest extends TestCase
{
	/**
	 * @var IAmsterdamReceiver
	 */
	private $receiver;

	public function setUp(): void
	{
		parent::setUp();

		$this->receiver = IAmsterdamReceiver::create(
			__DIR__ . '/../../../../data-sources/temps.xml'
		);
	}

	/**
	 * @test
	 */
	public function createWithIncorrectStabPath(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage("Unable to read file `abube`");

		IAmsterdamReceiver::create('abube');
	}

	/**
	 * @test
	 */
	public function createWithEmptyStabPath(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage("Unable to read file ``");

		IAmsterdamReceiver::create('');
	}

	/**
	 * @test
	 */
	public function obtain(): void
	{
		$result = iterator_to_array($this->receiver->obtain());

		$this->assertCount(11, $result);

		/** @var TemperaturePrediction $latestResult */
		$latestResult = end($result);

		$this->assertTrue($latestResult->temperature->scale->equals(Scale::celsius()));
		$this->assertEquals('03', $latestResult->temperature->value);
		$this->assertEquals('Amsterdam', $latestResult->city);
		$this->assertEquals('2018-01-12 10:00', $latestResult->date->format('Y-m-d H:i'));
	}
}
