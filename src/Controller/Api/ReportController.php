<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\ReportRequestDto;
use App\Service\ReportService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/vehicles/', name: 'api_vehicles_')]
class ReportController
{
    public function __construct(private readonly ReportService $reportService)
    {
    }

    #[Route('report', name: 'data_report', methods: ['GET'])]
    public function __invoke(
        #[MapQueryString(validationGroups: ['boundary'])] ReportRequestDto $reportRequestDto,
    ): JsonResponse
    {
        $result = $this->reportService->generateReport($reportRequestDto);

        if ($result->isRejected()) {
            return new JsonResponse(
                ['error' => 'Invalid report request'],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        if ($result->isNotFound()) {
            return new JsonResponse(
                ['error' => 'No vehicle or data found'],
                Response::HTTP_NOT_FOUND,
            );
        }

        return new JsonResponse([
            'vehicle'            => $result->getRegistrationPlates(),
            'from'               => $result->getFromDateTime()?->format(\DateTimeInterface::ATOM),
            'to'                 => $result->getToDateTime()?->format(\DateTimeInterface::ATOM),
            'distanceKm'         => round((int) $result->getDistanceTraveled() / 1000, 2),
            'fuelConsumedLitres' => round((int) $result->getFuelConsumed() / 1000, 2),
        ]);
    }
}
