<?php

declare(strict_types=1);

namespace App\Weather;

final class Scale
{
	/**
	 * @var int
	 */
	private $value;

	public static function celsius(): self
	{
		return new self(1);
	}

	public static function fahrenheit(): self
	{
		return new self(2);
	}

	public function equals(Scale $with): bool
	{
		return $this->value === $with->value;
	}

	public function toString(): string
	{
		return match ($this->value) {
			1 => 'celsius',
			2 => 'fahrenheit'
		};
	}

	public static function fromString(string $value): self
	{
		return match ($value) {
			'celsius' => new self(1),
			'fahrenheit' => new self(2),
			default => throw new \InvalidArgumentException('Incorrect scale type')
		};
	}

	private function __construct(int $value)
	{
		$this->value = $value;
	}
}
