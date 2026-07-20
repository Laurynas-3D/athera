<?php

namespace App\DTO;

class Payload
{
    public function __construct(
        public readonly ?string $deviceId = null,
        /** @var Record[] */
        public readonly array   $records = [],
    )
    {
    }
}
