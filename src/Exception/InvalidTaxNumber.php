<?php

declare(strict_types=1);

namespace Bradesco\Multipag\Exception;

class InvalidTaxNumber extends BradescoMultipagException
{
    public function __construct(string $number)
    {
        parent::__construct("Tax number '{$number}' is invalid");
    }
}
