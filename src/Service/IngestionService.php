<?php

namespace App\Service;

use App\DTO\IngestionResult;
use App\DTO\Payload;
use App\Entity\VehicleNumberPlates;
use App\Entity\VehicleRecord;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IngestionService
{
    public function __construct(
        private readonly ValidatorInterface     $validator,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger,
    )
    {
    }

    public function ingest(Payload $payload): IngestionResult
    {
        $ingestionResult = $this->validatePayloadAndRecords($payload);
        if ($ingestionResult->isPayloadAccepted()) {
            $this->saveRecords($payload, $ingestionResult->getAcceptedRecords());
            $this->saveNumberPlates($payload, $ingestionResult->getAcceptedRecords());
            $this->entityManager->flush();
        }

        return $ingestionResult;
    }

    private function saveRecords(Payload $payload, array $acceptedRecords): void
    {
        foreach ($acceptedRecords as $record) {
            $newRecord = new VehicleRecord();
            $newRecord->setDeviceId($payload->deviceId);
            $newRecord->setLatitude($record->gnss->latitude);
            $newRecord->setLongitude($record->gnss->longitude);

            $newRecord->setAltitude($record->io->altitude);
            $newRecord->setSpeed($record->io->speed);
            $newRecord->setIgnition($record->io->ignition);
            $newRecord->setMovement($record->io->movement);
            $newRecord->setGsmSignal($record->io->gsmSignal);
            $newRecord->setTotalOdometer($record->io->totalOdometer);
            $newRecord->setEngineTotalFuelUsed($record->io->engineTotalFuelUsed);
            $newRecord->setCreatedAt(new \DateTimeImmutable());

            $newRecord->setRecordedAt($this->convertTimestampToDatetime($record->gnss->timestamp));

            $this->entityManager->persist($newRecord);
        }
    }

    private function saveNumberPlates(Payload $payload, array $acceptedRecords): void
    {
        $repository = $this->entityManager->getRepository(VehicleNumberPlates::class);
        $part1 = null;
        $part2 = null;

        foreach ($acceptedRecords as $record) {
            $part1 ??= $record->io->vehicleRegistrationNumberPart1;
            $part2 ??= $record->io->vehicleRegistrationNumberPart2;

            if (null !== $part1 && null !== $part2) {
                break;
            }
        }

        $numberPlates = $repository->findOneBy([
            'deviceId' => $payload->deviceId,
        ]);

        if (null === $numberPlates) {
            $numberPlates = new VehicleNumberPlates();
            $numberPlates->setDeviceId($payload->deviceId);
        }
        if (null !== $part1 && $numberPlates->getVehicleRegistrationNumberPart1() !== $part1) {
            $numberPlates->setVehicleRegistrationNumberPart1($part1);
        }
        if (null !== $part2 && $numberPlates->getVehicleRegistrationNumberPart2() !== $part2) {
            $numberPlates->setVehicleRegistrationNumberPart2($part2);
        }

        $this->entityManager->persist($numberPlates);
    }

    private function validatePayloadAndRecords(Payload $payload): IngestionResult
    {
        $result = new IngestionResult();

        $payloadViolations = $this->validator->validate($payload);
        if (count($payloadViolations) > 0) {
            $result->reject($payloadViolations);

            return $result;
        }

        foreach ($payload->records as $record) {
            $recordViolations = $this->validator->validate($record);
            if (count($recordViolations) > 0) {
                $result->rejectRecord($record, $recordViolations);
            } else {
                $result->acceptRecord($record);
            }
        }

        return $result;
    }

    private function convertTimestampToDatetime(?float $timestamp): \DateTimeImmutable
    {
        if (null !== $timestamp) {
            return new \DateTimeImmutable()->setTimestamp((int)$timestamp);
        }
        return new \DateTimeImmutable();
    }

    public function logRejected(Payload $payload, array $rejectedItems): void
    {
        $violations = [];
        foreach ($rejectedItems as $rejected) {
            foreach ($rejected['violations'] as $violation) {
                $violations[] = sprintf(
                    '%s: %s',
                    $violation->getPropertyPath(),
                    $violation->getMessage()
                );
            }
        }
        $this->logger->warning('Rejected',
            [
                'deviceId' => $payload->deviceId,
                'violations' => $violations
            ]);
    }
}
