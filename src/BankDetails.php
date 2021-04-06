<?php

declare(strict_types=1);

namespace Bradesco\Multipag;

use Bradesco\Multipag\Exception\InvalidBankDetail;

class BankDetails
{
    private string $bankCode;
    private string $branchCode;
    private string $branchCodeDigit;
    private string $accountNumber;
    private string $accountNumberDigit;
    private AccountType $accountType;

    public function __construct(string $bankCode, string $branchCode, string $accountNumber, AccountType $accountType)
    {
        Assert::that(strlen($bankCode) === 3, InvalidBankDetail::dueToWrongBankCode($bankCode));

        $branch = self::splitNumberAndDigit($branchCode);
        $account = self::splitNumberAndDigit($accountNumber);

        $this->bankCode = $bankCode;
        $this->branchCode = $branch['number'];
        $this->branchCodeDigit = $branch['digit'];
        $this->accountNumber = $account['number'];
        $this->accountNumberDigit = $account['digit'];
        $this->accountType = $accountType;
    }

    public function getBankCode(): string
    {
        return $this->bankCode;
    }

    public function getBranchCode(): string
    {
        return $this->branchCode;
    }

    public function getBranchCodeDigit(): mixed
    {
        return $this->branchCodeDigit;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getAccountNumberDigit(): mixed
    {
        return $this->accountNumberDigit;
    }

    public function getAccountType(): string
    {
        return "{$this->accountType}";
    }

    /**
     * @return array{number:string, digit:string}
     */
    private static function splitNumberAndDigit(string $value): array
    {
        $value = str_replace([' ', '.'], '-', $value);

        $parts = explode('-', $value);

        return ['number' => $parts[0], 'digit' => $parts[1] ?? '0'];
    }
}
