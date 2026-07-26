<?php

namespace App\Repository;

use App\Entity\VehicleRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends VehicleRecordRepository<VehicleRecord>
 */
class VehicleRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleRecord::class);
    }

    /**
     * @return array<VehicleRecord>
     */
    public function findByDeviceInRange(string $deviceId, \DateTimeInterface $from, \DateTimeInterface $to): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.deviceId = :deviceId')
            ->andWhere('r.recordedAt BETWEEN :from AND :to')
            ->setParameter('deviceId', $deviceId)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('r.recordedAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
