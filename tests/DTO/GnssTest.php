<?php

declare(strict_types=1);

namespace App\Tests\DTO;

use App\DTO\Gnss;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class GnssTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidGnssHasNoViolations(): void
    {
        $gnss = new Gnss(
            timestamp: 1781849860.0,
            latitude: 54.6872,
            longitude: 25.2797,
        );

        self::assertCount(0, $this->validator->validate($gnss));
    }

    public function testMissingRequiredFieldsAreRejected(): void
    {
        $gnss = new Gnss();

        self::assertGreaterThan(0, $this->validator->validate($gnss)->count());
    }
}
