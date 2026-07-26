<?php

namespace App\Service;

use App\DTO\ReportRequestDto;
use App\DTO\ReportResultDto;
use App\Entity\VehicleNumberPlates;
use App\Repository\VehicleRecordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReportService
{
    public function __construct(
        private readonly ValidatorInterface     $validator,
        private readonly EntityManagerInterface $entityManager,
        private readonly VehicleRecordRepository   $vehicleRecordRepository
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

        $exploded = explode(' ', (string)$reportRequestDto->registrationPlates);
        $part1 = $exploded[0];
        $part2 = $exploded[1] ?? null;

        $numberPlates = $repository->findOneBy(
            [
                'vehicleRegistrationNumberPart1' => trim($part1),
                'vehicleRegistrationNumberPart2' => trim((string)$part2),
            ]);

        if (null === $numberPlates) {
            return $reportResultDto;
        }

        $metrics = $this->vehicleRecordRepository->getMetricsForDeviceInRange(
            $numberPlates->deviceId,
            $reportRequestDto->fromDateTime,
            $reportRequestDto->toDateTime,
        );

        $hasDelta = (int) $metrics['recordCount'] >= 2;

        $reportResultDto
            ->setDistanceTraveled($hasDelta ? (int) $metrics['maxOdometer'] - (int) $metrics['minOdometer'] : 0)
            ->setFuelConsumed($hasDelta ? (int) $metrics['maxFuel'] - (int) $metrics['minFuel'] : 0)
            ->setRegistrationPlates($numberPlates->getFullLicensePlateNumbers())
            ->setFromDateTime($reportRequestDto->fromDateTime)
            ->setToDateTime($reportRequestDto->toDateTime);

        return $reportResultDto;
    }

}
