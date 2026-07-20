<?php

namespace App\DTO;

class Record
{
    public function __construct(
        public readonly Gnss $gnss,
        public readonly IO   $io,
    )
    {
    }
}
