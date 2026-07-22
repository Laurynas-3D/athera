<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class Gnss
{
    public function __construct(
        #[Assert\NotNull]
        #[Assert\Type('float')]
        #[Assert\Positive]
        #[Assert\Range(
            min: 1577836800,    // 2020-01-01
            max: 2114380800     // 2037-01-01
        )]
        public ?float $timestamp = null,
        #[Assert\NotNull]
        #[Assert\Type('float')]
        public ?float $latitude = null,
        #[Assert\NotNull]
        #[Assert\Type('float')]
        public ?float $longitude = null,
    )
    {
    }
}
