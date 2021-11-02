<?php

declare(strict_types=1);

namespace App\Partners\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class WeatherPrediction
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(
	 *     type="string",
	 *     length=255
	 * )
	 *
	 * @var string
	 */
	private $handler;

	/**
	 * @ORM\Column(
	 *     type="string",
	 *     length=255
	 * )
	 *
	 * @var string
	 */
	private $city;

	/**
	 * @ORM\Column(
	 *     type="datetime"
	 * )
	 *
	 * @var \DateTime
	 */
	private $datetime;

	/**
	 * @ORM\Column(
	 *     type="string",
	 *      length=255
	 *  )
	 *
	 * @var string
	 */
	private $scale;

	/**
	 * @ORM\Column(
	 *     type="integer",
	 *      length=255
	 * )
	 *
	 * @var int
	 */
	private $value;

	/**
	 * @ORM\Column(
	 *     type="datetime"
	 * )
	 *
	 * @var \DateTime
	 */
	private $updatedAt;
}
