<?php

namespace App\DTO;

use DateTimeImmutable;

class ReportResultDto
{
    public function __construct(

        private ?DateTimeImmutable $fromDateTime = null,
        private ?DateTimeImmutable $toDateTime = null,
        private ?string            $registrationPlates = null,
        private ?int               $fuelConsumed = null,
        private ?int               $distanceTraveled = null,
        private bool               $resultRejected = false,
    )
    {
    }

    public function getToDateTime(): ?DateTimeImmutable
    {
        return $this->toDateTime;
    }

    public function setToDateTime(?DateTimeImmutable $toDateTime): self
    {
        $this->toDateTime = $toDateTime;
        return $this;
    }

    public function getFromDateTime(): ?DateTimeImmutable
    {
        return $this->fromDateTime;
    }

    public function setFromDateTime(?DateTimeImmutable $fromDateTime): self
    {
        $this->fromDateTime = $fromDateTime;
        return $this;
    }

    public function reject(): void
    {
        $this->resultRejected = true;
    }

    public function isRejected(): bool
    {
        return $this->resultRejected;
    }

    public function getRegistrationPlates(): ?string
    {
        return $this->registrationPlates;
    }

    public function setRegistrationPlates(?string $registrationPlates): self
    {
        $this->registrationPlates = $registrationPlates;
        return $this;
    }

    public function getFuelConsumed(): ?int
    {
        return $this->fuelConsumed;
    }

    public function setFuelConsumed(?int $fuelConsumed): self
    {
        $this->fuelConsumed = $fuelConsumed;
        return $this;
    }

    public function getDistanceTraveled(): ?int
    {
        return $this->distanceTraveled;
    }

    public function setDistanceTraveled(?int $distanceTraveled): self
    {
        $this->distanceTraveled = $distanceTraveled;
        return $this;
    }

    public function isResultRejected(): bool
    {
        return $this->resultRejected;
    }

    public function setResultRejected(bool $resultRejected): self
    {
        $this->resultRejected = $resultRejected;
        return $this;
    }

    public function isNotFound(): bool
    {
        return $this->registrationPlates === null;
    }

}
