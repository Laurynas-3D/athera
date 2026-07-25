<?php

namespace App\Service;

use App\DTO\IngestionResult;
use App\DTO\Payload;
use App\DTO\ReportRequestDto;
use App\DTO\ReportResultDto;
use App\Entity\VehicleNumberPlates;
use App\Entity\VehicleRecord;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReportService
{
    public function __construct(
        private readonly ValidatorInterface     $validator,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger, // TODO: implement to a log
    )
    {
    }

    public function generateReport(ReportRequestDto $reportRequestDto): ReportResultDto
    {
        $reportResultDto = new ReportResultDto();
        $validated = $this->validator->validate($reportRequestDto);
        if (count($validated) > 0) {
            $reportResultDto->reject();
            return $reportResultDto;
        }

        return $this->getVehicleData($reportRequestDto, $reportResultDto);
    }

    private function getVehicleData(ReportRequestDto $reportRequestDto, ReportResultDto $reportResultDto): ReportResultDto
    {
        $repository = $this->entityManager->getRepository(VehicleNumberPlates::class);

        $exploded = explode(' ', $reportRequestDto->registrationPlates);
        $part1 = $exploded[0] ?? null;
        $part2 = $exploded[1] ?? null;

        $numberPlates = $repository->findOneBy(
            [
                'vehicleRegistrationNumberPart1' => trim($part1),
                'vehicleRegistrationNumberPart2' => trim($part2),
            ]);

        if (null === $numberPlates) {
            return $reportResultDto;
        }

        $records = $this->entityManager->getRepository(VehicleRecord::class)->findBy(['deviceId' => $numberPlates->getDeviceId()]);


        $totalDistance = 0;
        $fuelConsumed = 0;
        $from = $reportRequestDto->fromDateTime;
        $to = $reportRequestDto->toDateTime;

        /** @var VehicleRecord $record */
        foreach ($records as $record) {
            $totalDistance += (int)$record->getTotalOdometer();
            $fuelConsumed += (int)$record->getEngineTotalFuelUsed();
        }
        $reportResultDto->setDistanceTraveled($totalDistance)
            ->setFuelConsumed($fuelConsumed)
            ->setRegistrationPlates($numberPlates->getFullLicensePlateNumbers())
            ->setFromDateTime($from)
            ->setToDateTime($to);

        return $reportResultDto;
    }

}
