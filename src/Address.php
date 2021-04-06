<?php

declare(strict_types=1);

namespace Bradesco\Multipag;

use Bradesco\Multipag\Exception\InvalidAddress;

class Address
{
    private string $street;
    private int $number;
    private string $additionalDetails;
    private string $neighborhood;
    private string $city;
    private string $postalCode;
    private string $state;

    public function __construct(
        string $street,
        int $number,
        string $additionalDetails,
        string $neighborhood,
        string $city,
        string $postalCode,
        string $state,
    ) {
        $postalCode = preg_replace('/[^0-9]/', '', "{$postalCode}");
        $postalCode = "{$postalCode}";

        Assert::that(strlen($state) === 2, InvalidAddress::dueToWrongStateLength($state));
        Assert::that(strlen($postalCode) === 8, InvalidAddress::dueToWrongPostalCode($postalCode));

        $this->street = $street;
        $this->number = $number;
        $this->additionalDetails = $additionalDetails;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->postalCode = "{$postalCode}";
        $this->state = $state;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getAdditionalDetails(): string
    {
        return $this->additionalDetails;
    }

    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getState(): string
    {
        return $this->state;
    }
}
