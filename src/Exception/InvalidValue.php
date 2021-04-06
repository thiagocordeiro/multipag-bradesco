<?php

declare(strict_types=1);

namespace Bradesco\Multipag\Exception;

class InvalidValue extends BradescoMultipagException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function valueIsToLong(string|int $value, int $max): self
    {
        return new self(
            sprintf(
                'Value \'%s\' is invalid. The maximum length for this value is %s, %s given',
                $value,
                $max,
                strlen("{$value}"),
            ),
        );
    }

    public static function valueIsNegative(float $value): self
    {
        return new self(sprintf('Value \'%s\' is invalid. The value must be zero or grater', $value));
    }
}
