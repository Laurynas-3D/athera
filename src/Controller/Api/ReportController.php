<?php

namespace App\Controller\Api;

use App\DTO\ReportRequestDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/vehicles/', name: 'api_vehicles_')]
class ReportController
{
    #[Route('report', name: 'data_report', methods: ['GET'])]
    public function __invoke(
        #[MapQueryString] ReportRequestDto $reportRequestDto,
    ): JsonResponse
    {

        dd($reportRequestDto);
    }
}
