<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class Gnss
{
    public function __construct(
        #[Assert\NotNull]
        public ?float $timestamp = null,
        #[Assert\NotNull]
        public ?float $latitude = null,
        #[Assert\NotNull]
        public ?float $longitude = null,
    )
    {
    }
}
