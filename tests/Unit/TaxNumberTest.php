<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit;

use Bradesco\Multipag\Exception\InvalidTaxNumber;
use Bradesco\Multipag\TaxNumber;
use PHPUnit\Framework\TestCase;

class TaxNumberTest extends TestCase
{
    public function testCreateCpf(): void
    {
        $value = '123.123.123-12';

        $number = new TaxNumber($value);

        $this->assertEquals('1', $number->getType());
        $this->assertEquals('12312312312', $number->getNumber());
    }

    public function testCreateCnpj(): void
    {
        $value = '12.123.123/0001-12';

        $number = new TaxNumber($value);

        $this->assertEquals('2', $number->getType());
        $this->assertEquals('12123123000112', $number->getNumber());
    }

    public function testInvalidTaxNumber(): void
    {
        $this->expectException(InvalidTaxNumber::class);
        $this->expectExceptionMessage("Tax number '123321' is invalid");

        new TaxNumber('123321');
    }
}
