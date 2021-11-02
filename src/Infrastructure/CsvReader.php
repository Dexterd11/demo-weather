<?php

declare(strict_types=1);

namespace App\Infrastructure;

interface CsvReader
{
	public function read(string $filePath): \Iterator;
}
