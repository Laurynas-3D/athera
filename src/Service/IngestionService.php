<?php

namespace App\Service;
use App\DTO\IngestionResult;
use App\DTO\Payload;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IngestionService
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        public IngestionResult $ingestionResult,
    ){}
    public function ingest(Payload $payload): IngestionResult
    {
        $result = new IngestionResult();

        $payloadViolations = $this->validator->validate($payload);
        if (count($payloadViolations) > 0) {
            $result->reject();

            return $result;
        }

        foreach ($payload->records as $record) {
            $recordViolations = $this->validator->validate($record);
            if (count($recordViolations) > 0) {
                $result->rejectRecord($record, $recordViolations);
                continue;
            }
        }

        $result->acceptRecord($record);
        return $result;
    }
}
