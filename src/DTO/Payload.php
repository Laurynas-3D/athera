<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Payload
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly ?string $deviceId = null,
        #[Assert\Count(min: 1)]
        /** @var Record[] */
        public readonly array   $records = [],
    )
    {
    }
}
