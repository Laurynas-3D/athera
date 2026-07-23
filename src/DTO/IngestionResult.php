<?php

namespace App\DTO;
class IngestionResult
{
    public function __construct(
        private ?array $accepted = [],
        private ?array $rejected = [],
        private ?array $rejectedPayload = [],
        private ?bool  $payloadAccepted = true,
    )
    {
    }

    public function reject(mixed $violations): void
    {
        $this->payloadAccepted = false;
        $this->rejectedPayload[] = ['violations' => $violations];
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

    public function isPayloadRejected(): bool
    {
        return $this->payloadAccepted === false;
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

    public function getRejectedPayload(): array
    {
        return $this->rejectedPayload;
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
