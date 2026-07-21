<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Gnss
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly ?float $timestamp = null,
        #[Assert\NotBlank]
        public readonly ?float $latitude = null,
        #[Assert\NotBlank]
        public readonly ?float $longitude = null,
    )
    {
    }
}
