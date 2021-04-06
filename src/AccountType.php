<?php

declare(strict_types=1);

namespace Bradesco\Multipag;

use Bradesco\Multipag\Exception\InvalidAccountType;

class AccountType
{
    private const TYPE_CHECKING = 'CC';
    private const TYPE_SAVING = 'PP';
    private const TYPES = [self::TYPE_CHECKING, self::TYPE_SAVING];

    private string $type;

    /**
     * @throws InvalidAccountType
     */
    public function __construct(string $type)
    {
        if (false === in_array($type, self::TYPES, true)) {
            throw new InvalidAccountType($type);
        }

        $this->type = $type;
    }

    public static function checking(): self
    {
        return new self(self::TYPE_CHECKING);
    }

    public static function saving(): self
    {
        return new self(self::TYPE_SAVING);
    }

    public function isChecking(): bool
    {
        return $this->type === self::TYPE_CHECKING;
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
