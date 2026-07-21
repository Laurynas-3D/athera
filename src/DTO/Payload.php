<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Payload
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly ?string $deviceId = null,
        /** @var Record[] */
        public readonly array   $records = [],
    )
    {
    }
}
