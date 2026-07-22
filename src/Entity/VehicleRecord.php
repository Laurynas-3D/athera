<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(name: 'idx_device_time', columns: ['device_id', 'recorded_at'])]
class VehicleRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING,length: 128, nullable: false)]
    private string $deviceId;
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $altitude = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $speed = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $ignition = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $movement = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, columnDefinition: 'INT UNSIGNED')]
    private ?int $gsmSignal = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?int $totalOdometer = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?int $engineTotalFuelUsed = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $recordedAt;

    public function __construct()
    {
        $this->recordedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(string $deviceId): self
    {
        $this->deviceId = $deviceId;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getEngineTotalFuelUsed(): ?int
    {
        return $this->engineTotalFuelUsed;
    }

    public function setEngineTotalFuelUsed(?int $engineTotalFuelUsed): self
    {
        $this->engineTotalFuelUsed = $engineTotalFuelUsed;
        return $this;
    }

    public function getTotalOdometer(): ?int
    {
        return $this->totalOdometer;
    }

    public function setTotalOdometer(?int $totalOdometer): self
    {
        $this->totalOdometer = $totalOdometer;
        return $this;
    }

    public function getGsmSignal(): ?int
    {
        return $this->gsmSignal;
    }

    public function setGsmSignal(?int $gsmSignal): self
    {
        $this->gsmSignal = $gsmSignal;
        return $this;
    }

    public function getMovement(): ?int
    {
        return $this->movement;
    }

    public function setMovement(?int $movement): self
    {
        $this->movement = $movement;
        return $this;
    }

    public function getIgnition(): ?int
    {
        return $this->ignition;
    }

    public function setIgnition(?int $ignition): self
    {
        $this->ignition = $ignition;
        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(?int $speed): self
    {
        $this->speed = $speed;
        return $this;
    }

    public function getAltitude(): ?int
    {
        return $this->altitude;
    }

    public function setAltitude(?int $altitude): self
    {
        $this->altitude = $altitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getRecordedAt(): DateTimeImmutable
    {
        return $this->recordedAt;
    }

    public function setRecordedAt(DateTimeImmutable $recordedAt): self
    {
        $this->recordedAt = $recordedAt;
        return $this;
    }

}
