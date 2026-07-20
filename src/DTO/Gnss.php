<?php

namespace App\DTO;

class Gnss
{
    public function __construct(
        public readonly ?float $timestamp = null,
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null,
    )
    {
    }
}
