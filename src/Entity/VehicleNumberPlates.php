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
    public string $deviceId = '' {
        get {
            return $this->deviceId;
        }
        set(?string $value) {
            $this->deviceId = $value;
        }
    }

    #[ORM\Column(type: Types::STRING, length: 12, nullable: true, index: true)]
    public ?string $vehicleRegistrationNumberPart1 = null {
        get {
            return $this->vehicleRegistrationNumberPart1;
        }
        set {
            $this->vehicleRegistrationNumberPart1 = $value;
        }
    }

    #[ORM\Column(type: Types::STRING, length: 12, nullable: true, index: true)]
    public ?string $vehicleRegistrationNumberPart2 = null {
        get {
            return $this->vehicleRegistrationNumberPart2;
        }
        set {
            $this->vehicleRegistrationNumberPart2 = $value;
        }
    }

    public function getFullLicensePlateNumbers(): string
    {
        return $this->vehicleRegistrationNumberPart1 . '+' . $this->vehicleRegistrationNumberPart2;
    }

}
