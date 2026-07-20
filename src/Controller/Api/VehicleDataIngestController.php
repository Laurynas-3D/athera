<?php

namespace App\Controller\Api;

use App\DTO\Payload;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/vehicles/', name: 'api_vehicles_data_')]
class VehicleDataIngestController
{
    #[Route('records', name: 'record', methods: ['POST'])]
    public function record(
        Request $request,
        #[MapRequestPayload] Payload $payload,
    ): JsonResponse
    {
        dd($payload);
    }
}

//{
//    "deviceId" : "AVLDID5000",
//    "records":
//        [
//            {
//                "gnss": {
//                "timestamp": 1781849860.548,
//                    "latitude": 54.6872,
//                    "longitude": 25.2797
//                },
//                "io": {
//                "4": 1,
//                    "24": 40,
//                    "239": 1,
//                    "240": 1,
//                    "21": 3,
//                    "216": 120000,
//                    "86": 1200,
//                    "231": "ATH 123",
//                    "232": "124"
//                }
//            },
//            {
//                "gnss": {
//                "timestamp": 1781849999,
//                    "latitude": 54.6700,
//                    "longitude": 25.2900
//                },
//                "io": {
//                "4": 1,
//                    "21": 4,
//                    "216": 120010,
//                    "86": 12050
//                }
//            }
//        ]
//}
