<?php

declare(strict_types=1);

namespace Bradesco\Multipag;

use Throwable;

class Assert
{
    /**
     * Standard php assertion requires additional configuration to behave properly
     * while webmozarts/assert throws their own exception and we have less catch control over it.
     * For this reason we implement our own assertion
     */
    public static function that(bool $assertion, Throwable $exception): void
    {
        if ($assertion === true) {
            return;
        }

        throw $exception;
    }
}
