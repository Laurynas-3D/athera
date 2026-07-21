<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class VehicleNumberPlates
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 128, unique: true, nullable: false, index: true)]
    private ?string $deviceId = null;

    #[ORM\Column(type: Types::STRING, length: 12, nullable: true, index: true)]
    private ?string $vehicleRegistrationNumberPart1 = null;

    #[ORM\Column(type: Types::STRING, length: 12, nullable: true, index: true)]
    private ?string $vehicleRegistrationNumberPart2 = null;

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function getFullLicensePlateNumbers(): string
    {
        return $this->vehicleRegistrationNumberPart1 . '+' . $this->vehicleRegistrationNumberPart2;
    }

    public function setDeviceId(?string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    public function getVehicleRegistrationNumberPart1(): ?string
    {
        return $this->vehicleRegistrationNumberPart1;
    }

    public function setVehicleRegistrationNumberPart1(?string $vehicleRegistrationNumberPart1): void
    {
        $this->vehicleRegistrationNumberPart1 = $vehicleRegistrationNumberPart1;
    }

    public function getVehicleRegistrationNumberPart2(): ?string
    {
        return $this->vehicleRegistrationNumberPart2;
    }

    public function setVehicleRegistrationNumberPart2(?string $vehicleRegistrationNumberPart2): void
    {
        $this->vehicleRegistrationNumberPart2 = $vehicleRegistrationNumberPart2;
    }

}
