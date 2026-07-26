<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

class IO
{
    public function __construct(
        #[SerializedName('4')]
        public readonly ?int    $altitude = null,
        #[SerializedName('24')]
        public readonly ?int    $speed = null,
        #[SerializedName('239')]
        public readonly ?int   $ignition = null,
        #[SerializedName('240')]
        public readonly ?int   $movement = null,
        #[SerializedName('21')]
        public readonly ?int    $gsmSignal = null,
        #[SerializedName('216')]
        public readonly ?int    $totalOdometer = null,
        #[SerializedName('86')]
        public readonly ?int    $engineTotalFuelUsed = null,
        #[SerializedName('231')]
        public readonly ?string $vehicleRegistrationNumberPart1 = null,
        #[SerializedName('232')]
        public readonly ?string $vehicleRegistrationNumberPart2 = null,
    )
    {
    }
}
