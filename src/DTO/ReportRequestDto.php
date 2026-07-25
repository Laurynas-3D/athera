<?php

namespace App\DTO;

use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class ReportRequestDto
{
    public function __construct(
        #[SerializedName('from')]
        #[Assert\NotNull(message: 'date_from is required')]
        public readonly ?DateTimeImmutable    $fromDateTime = null,
        #[SerializedName('to')]
        #[Assert\NotNull(message: 'date_to is required')]
        #[Assert\GreaterThan(propertyPath: 'fromDateTime', message: 'date_to must be greater than date_from')]
        public readonly ?DateTimeImmutable    $toDateTime = null,
        #[SerializedName('plates')]
        #[Assert\NotBlank(message: 'The vehicle registration number is required')]
        public readonly ?string   $registrationPlates = null,
    )
    {
    }
}

