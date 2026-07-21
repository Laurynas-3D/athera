<?php

namespace App\DTO;
class IngestionResult
{
    public function __construct(
        private ?array    $accepted = null,
        private ?array    $rejected = null,
        private ?bool   $payloadRejected = null,
    )
    {
    }

    public function reject(): void
    {
        $this->payloadRejected = true;
    }
    public function acceptRecord(Record $record): void
    {
        $this->accepted[] = $record;
    }

    public function rejectRecord(Record $record, mixed $violations): void
    {
        $this->rejected[] = ['record' => $record, 'violations' => $violations];
    }

    public function isPayloadRejected(): bool
    {
        return $this->payloadRejected;
    }

    public function getAcceptedCount(): int
    {
        return count($this->accepted);
    }

    public function getRejectedCount(): int
    {
        return count($this->rejected);
    }

    public function hasErrors(): bool
    {
        return $this->payloadRejected || $this->getRejectedCount() > 0;
    }
}
