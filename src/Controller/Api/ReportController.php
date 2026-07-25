<?php

namespace App\Controller\Api;

use App\DTO\ReportRequestDto;
use App\Service\ReportService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/vehicles/', name: 'api_vehicles_')]
class ReportController
{
    public function __construct(private ReportService $reportService)
    {
    }

    #[Route('report', name: 'data_report', methods: ['GET'])]
    public function __invoke(
        #[MapQueryString(validationGroups: ['boundary'])] ReportRequestDto $reportRequestDto,
    ): JsonResponse
    {
        $data = $this->reportService->generateReport($reportRequestDto);
        dd($reportRequestDto);
    }
}
