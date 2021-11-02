<?php

declare(strict_types=1);

namespace App\Partners\DataReceiver;

interface PartnerDataReceiver
{
	public function name(): string;

	public function obtain(): \Iterator;
}
