<?php

declare(strict_types=1);

namespace Bradesco\Multipag\Exception;

class InvalidAddress extends BradescoMultipagException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function dueToWrongStateLength(string $state): self
    {
        return new self("State '{$state}' is invalid. The value must contain exactly 2 digits");
    }

    public static function dueToWrongPostalCode(string $postalCode): self
    {
        return new self("Postal code '{$postalCode}' is invalid. The value must contain exactly 8 digits");
    }
}
