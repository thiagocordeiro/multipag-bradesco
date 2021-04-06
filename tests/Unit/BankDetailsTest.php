<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit;

use Bradesco\Multipag\AccountType;
use Bradesco\Multipag\BankDetails;
use Bradesco\Multipag\Exception\InvalidBankDetail;
use PHPUnit\Framework\TestCase;

class BankDetailsTest extends TestCase
{
    /**
     * @dataProvider invalidBankCodeDataset
     */
    public function testInvalidBankCode(string $code): void
    {
        $this->expectException(InvalidBankDetail::class);
        $this->expectExceptionMessage("Bank code '{$code}' is invalid. The value must contain exactly 3 digits");

        new BankDetails($code, '1234', '5656', AccountType::checking());
    }

    /**
     * @dataProvider branchNumberWithDigitDataset
     */
    public function testBranchCode(string $code, string $number, string $digit): void
    {
        $details = new BankDetails('237', $code, '5656', AccountType::checking());

        $this->assertEquals($number, $details->getBranchCode());
        $this->assertEquals($digit, $details->getBranchCodeDigit());
    }

    /**
     * @dataProvider branchNumberWithDigitDataset
     */
    public function testAccountNumber(string $code, string $number, string $digit): void
    {
        $details = new BankDetails('237', '5656', $code, AccountType::checking());

        $this->assertEquals($number, $details->getAccountNumber());
        $this->assertEquals($digit, $details->getAccountNumberDigit());
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function invalidBankCodeDataset(): array
    {
        return [
            '1 digit' => ['code' => '1'],
            '2 digits' => ['code' => '12'],
            '4 digits' => ['code' => '1234'],
            '5 digits' => ['code' => '12345'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function branchNumberWithDigitDataset(): array
    {
        return [
            'space' => ['code' => '1234 5', 'number' => '1234', 'digit' => '5'],
            'dash' => ['code' => '1234-5', 'number' => '1234', 'digit' => '5'],
            'point' => ['code' => '1234.5', 'number' => '1234', 'digit' => '5'],
            'missing' => ['code' => '1234', 'number' => '1234', 'digit' => '0'],
        ];
    }
}
