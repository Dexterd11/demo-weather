<?php

declare(strict_types=1);

namespace App\Partners\DataReceiver;

function checkFilePath(string $filePath): void
{
	if (file_exists($filePath) === false || is_readable($filePath) === false) {
		throw new \InvalidArgumentException(
			sprintf('Unable to read file `%s`', $filePath)
		);
	}
}

function datetimeInstantiator(?string $datetimeString, string $withFormat, $timezone = null): ?\DateTimeImmutable
{
	if ($datetimeString !== null && $datetimeString !== '') {
		try {
			$timezone = timezoneFactory($timezone);

			return \DateTimeImmutable::createFromFormat($withFormat, $datetimeString, $timezone);
		} catch (\Throwable $throwable) {
			throw new \RuntimeException(
				sprintf('Unable to create datetime from `%s`: %s', $datetimeString, $throwable->getMessage())
			);
		}
	}

	return null;
}

function timezoneFactory($timezone = null): ?\DateTimeZone
{
	if (is_string($timezone) && $timezone !== '') {
		$timezone = new \DateTimeZone($timezone);
	}

	/** @var \DateTimeZone|null $timezone */

	return $timezone;
}
