<?php

declare(strict_types=1);

namespace App\Tests\DTO;

use App\DTO\ReportRequestDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ReportRequestDtoTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidRequestHasNoViolations(): void
    {
        $dto = new ReportRequestDto(
            fromDateTime: new \DateTimeImmutable('2026-06-18T00:00:00+00:00'),
            toDateTime: new \DateTimeImmutable('2026-06-18T23:59:59+00:00'),
            registrationPlates: 'ABC 123',
        );

        self::assertCount(0, $this->validator->validate($dto));
    }

    public function testToBeforeFromIsRejected(): void
    {
        $dto = new ReportRequestDto(
            fromDateTime: new \DateTimeImmutable('2026-06-18T23:59:59+00:00'),
            toDateTime: new \DateTimeImmutable('2026-06-18T00:00:00+00:00'),
            registrationPlates: 'ABC 123',
        );

        self::assertGreaterThan(0, $this->validator->validate($dto)->count());
    }

    public function testMissingPlateIsRejected(): void
    {
        $dto = new ReportRequestDto(
            fromDateTime: new \DateTimeImmutable('2026-06-18T00:00:00+00:00'),
            toDateTime: new \DateTimeImmutable('2026-06-18T23:59:59+00:00'),
            registrationPlates: null,
        );

        self::assertGreaterThan(0, $this->validator->validate($dto)->count());
    }
}
