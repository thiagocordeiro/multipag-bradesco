<?php

declare(strict_types=1);

namespace Bradesco\Multipag\Exception;

class InvalidBankDetail extends BradescoMultipagException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function dueToWrongBankCode(string $bankCode): self
    {
        return new self("Bank code '{$bankCode}' is invalid. The value must contain exactly 3 digits");
    }
}
