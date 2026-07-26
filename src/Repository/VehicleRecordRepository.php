<?php

namespace App\Repository;

use App\Entity\VehicleRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehicleRecord>
 */
class VehicleRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleRecord::class);
    }

    /**
     * @return array{minOdometer: ?string, maxOdometer: ?string, minFuel: ?string, maxFuel: ?string, recordCount: int}
     */
    public function getMetricsForDeviceInRange(string $deviceId, \DateTimeInterface $from, \DateTimeInterface $to): array
    {
        return $this->createQueryBuilder('r')
            ->select(
                'MIN(r.totalOdometer) AS minOdometer',
                'MAX(r.totalOdometer) AS maxOdometer',
                'MIN(r.engineTotalFuelUsed) AS minFuel',
                'MAX(r.engineTotalFuelUsed) AS maxFuel',
                'COUNT(r.id) AS recordCount',
            )
            ->andWhere('r.deviceId = :deviceId')
            ->andWhere('r.recordedAt BETWEEN :from AND :to')
            ->setParameter('deviceId', $deviceId)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getSingleResult();
    }
}
