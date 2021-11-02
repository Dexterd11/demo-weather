<?php

declare(strict_types=1);

namespace App\Infrastructure;

final class SimpleCsvFileReader implements CsvReader
{
	public function read(string $filePath): \Iterator
	{
		$resource = fopen($filePath, 'rb');

		$index = 0;

		try {
			while (!feof($resource)) {
				$row = fgetcsv($resource, 4096);

				if ($index !== 0 && $row !== false) {
					yield $row;
				}

				$index++;

			}
		} finally {
			fclose($resource);
		}
	}
}
