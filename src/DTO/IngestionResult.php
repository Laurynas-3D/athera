<?php

namespace App\DTO;
class IngestionResult
{
    public function __construct(
        private ?array    $accepted = [],
        private ?array    $rejected = [],
        private ?bool   $payloadAccepted = true,
    )
    {
    }

    public function reject(): void
    {
        $this->payloadAccepted = false;
    }
    public function acceptRecord(Record $record): void
    {
        $this->accepted[] = $record;
    }

    public function rejectRecord(Record $record, mixed $violations): void
    {
        $this->rejected[] = ['record' => $record, 'violations' => $violations];
    }

    public function isPayloadAccepted(): bool
    {
        return $this->payloadAccepted;
    }

    public function getAcceptedCount(): int
    {
        return count($this->accepted);
    }

    public function getAcceptedRecords(): array
    {
        return $this->accepted;
    }

    public function getRejectedRecords(): array
    {
        return $this->rejected;
    }
    public function getRejectedCount(): int
    {
        return count($this->rejected);
    }

    public function hasErrors(): bool
    {
        return $this->payloadAccepted === false || $this->getRejectedCount() > 0;
    }
}
