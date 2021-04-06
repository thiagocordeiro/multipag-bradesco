<?php

declare(strict_types=1);

namespace Bradesco\Multipag;

class TransferAccount
{
    private string $name;
    private TaxNumber $taxNumber;
    private Address $address;
    private BankDetails $bankDetails;

    public function __construct(string $name, TaxNumber $taxNumber, Address $address, BankDetails $bankDetails)
    {
        $this->name = $name;
        $this->taxNumber = $taxNumber;
        $this->address = $address;
        $this->bankDetails = $bankDetails;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTaxNumber(): TaxNumber
    {
        return $this->taxNumber;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getBankDetails(): BankDetails
    {
        return $this->bankDetails;
    }
}
