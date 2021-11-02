<?php

declare(strict_types=1);

namespace App\Partners\Commands;

use App\Partners\DataImporter\ImportedPrediction;
use App\Partners\DataImporter\PartnerDataImporter;
use App\Weather\Scale;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportPredictionsCommand extends Command
{
	private const SAVE_SQL = <<<END
INSERT INTO weather_prediction (handler, city, datetime, scale, value, updated_at) VALUES (?, ?, ?, ?, ?, ?)
ON CONFLICT (handler, city, datetime)
DO UPDATE SET scale = ?, value = ?, updated_at = ?
END;

	/**
	 * @var PartnerDataImporter
	 */
	private $dataImporter;

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	public function __construct(PartnerDataImporter $dataImporter, EntityManagerInterface $entityManager)
	{
		$this->dataImporter = $dataImporter;
		$this->entityManager = $entityManager;

		parent::__construct(null);
	}

	protected function configure(): void
	{
		$iterator = $this->dataImporter->process(Scale::celsius());

		$this->entityManager->getConnection()->transactional(
			static function (Connection $connection) use ($iterator): void {
				/** @var ImportedPrediction $prediction */
				foreach ($iterator as $prediction) {
					$currentDate = date('c');
					$scaleAsString = $prediction->prediction->scale->toString();

					$connection->executeQuery(self::SAVE_SQL, [
						$prediction->receiver,
						$prediction->city,
						$prediction->date->format('c'),
						$scaleAsString,
						(int)$prediction->prediction->value,
						$currentDate,
						$scaleAsString,
						(int)$prediction->prediction->value,
						$currentDate
					]);
				}
			}
		);

		$this->setName('predictions:update');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		return Command::SUCCESS;
	}
}
