<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Record
{
    public function __construct(
        #[Assert\Valid]
        public readonly Gnss $gnss,
        #[Assert\Valid]
        public readonly IO   $io,
    )
    {
    }
}
