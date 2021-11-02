<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Weather\Scale;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
final class WeatherApiController
{
	/**
	 * @Route(
	 *     "/weather",
	 *     methods={"GET"},
	 *     name="weather"
	 * )
	 */
	public function predictions(Request $request, WeatherAveragePresenter $averagePresenter): Response
	{
		/**
		 * @todo: Move validation to argument resolver
		 */
		$city = $request->query->get('city', 'Amsterdam');
		$scale = $request->query->get('scale', 'celsius');

		$result = $averagePresenter->averageReport(
			$city,
			new \DateTimeImmutable('2018-01-12 00:00:00'),
			new \DateTimeImmutable('2018-01-12 23:59:59'),
			Scale::fromString($scale)
		);

		return new JsonResponse($result);
	}
}
