<?php

namespace App\Service;

use App\DTO\ReportRequestDto;
use App\DTO\ReportResultDto;
use App\Entity\VehicleNumberPlates;
use App\Entity\VehicleRecord;
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

        $records = $this->vehicleRecordRepository->findByDeviceInRange(
            $numberPlates->deviceId,
            $reportRequestDto->fromDateTime,
            $reportRequestDto->toDateTime,
        );

        $reportResultDto->setDistanceTraveled($this->calculateDistance($records))
            ->setFuelConsumed($this->calculateFuelConsumed($records))
            ->setRegistrationPlates($numberPlates->getFullLicensePlateNumbers())
            ->setFromDateTime($reportRequestDto->fromDateTime)
            ->setToDateTime($reportRequestDto->toDateTime);

        return $reportResultDto;
    }

    /**
     * @param array<VehicleRecord> $records
     */
    private function calculateDistance(array $records): int
    {
        if (count($records) < 2) {
            return 0;
        }

        $min = (int)$records[0]->getTotalOdometer();
        $max = $min;

        foreach ($records as $record) {
            $odometer = (int)$record->getTotalOdometer();
            $min = min($min, $odometer);
            $max = max($max, $odometer);
        }

        return $max - $min;
    }

    /**
     * @param array<VehicleRecord> $records
     */
    private function calculateFuelConsumed(array $records): int
    {
        if (count($records) < 2) {
            return 0;
        }

        $min = (int)$records[0]->getEngineTotalFuelUsed();
        $max = $min;

        foreach ($records as $record) {
            $fuel = (int)$record->getEngineTotalFuelUsed();
            $min = min($min, $fuel);
            $max = max($max, $fuel);
        }

        return $max - $min;
    }

}
