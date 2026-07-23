<?php

namespace App\Controller\Api;

use App\DTO\Payload;
use App\Service\IngestionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/vehicles/', name: 'api_vehicles_data_')]
class VehicleDataIngestController
{
    #[Route('records', name: 'record', methods: ['POST'])]
    public function record(
        #[MapRequestPayload(validationGroups: ['boundary'])] Payload $payload,
        IngestionService                                             $ingestionService,
    ): JsonResponse
    {
        $ingestionResult = $ingestionService->ingest($payload);

        if ($ingestionResult->isPayloadRejected()) {
            $ingestionService->logRejected($payload, $ingestionResult->getRejectedPayload());
            return new JsonResponse(
                [
                    'batchAccepted' => false,
                    'deviceId' => $payload->deviceId ?? 'unknown',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($ingestionResult->hasErrors()) {
            $ingestionService->logRejected($payload, $ingestionResult->getRejectedRecords());
        }

        return new JsonResponse([
            'batchAccepted' => $ingestionResult->isPayloadAccepted(),
            'acceptedCount' => $ingestionResult->getAcceptedCount(),
            'rejectedCount' => $ingestionResult->getRejectedCount(),
        ]);
    }
}
