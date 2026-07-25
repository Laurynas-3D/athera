<?php

namespace App\DTO;

use DateTimeImmutable;

class ReportResultDto
{
    public function __construct(

        private ?DateTimeImmutable $fromDateTime = null,
        private ?DateTimeImmutable $toDateTime = null,
        private ?string            $registrationPlates = null,
        private ?string            $fuelConsumed = null,
        private ?string            $distanceTraveled = null,
        private bool               $resultRejected = false,
    )
    {
    }

    public function getToDateTime(): ?DateTimeImmutable
    {
        return $this->toDateTime;
    }

    public function setToDateTime(?DateTimeImmutable $toDateTime): ReportResultDto
    {
        $this->toDateTime = $toDateTime;
        return $this;
    }

    public function getFromDateTime(): ?DateTimeImmutable
    {
        return $this->fromDateTime;
    }

    public function setFromDateTime(?DateTimeImmutable $fromDateTime): ReportResultDto
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

    public function setRegistrationPlates(?string $registrationPlates): ReportResultDto
    {
        $this->registrationPlates = $registrationPlates;
        return $this;
    }

    public function getFuelConsumed(): ?string
    {
        return $this->fuelConsumed;
    }

    public function setFuelConsumed(?string $fuelConsumed): ReportResultDto
    {
        $this->fuelConsumed = $fuelConsumed;
        return $this;
    }

    public function getDistanceTraveled(): ?string
    {
        return $this->distanceTraveled;
    }

    public function setDistanceTraveled(?string $distanceTraveled): ReportResultDto
    {
        $this->distanceTraveled = $distanceTraveled;
        return $this;
    }

    public function isResultRejected(): bool
    {
        return $this->resultRejected;
    }

    public function setResultRejected(bool $resultRejected): ReportResultDto
    {
        $this->resultRejected = $resultRejected;
        return $this;
    }

}
