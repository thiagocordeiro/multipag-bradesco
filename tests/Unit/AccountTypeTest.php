<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit;

use Bradesco\Multipag\AccountType;
use Bradesco\Multipag\Exception\InvalidAccountType;
use PHPUnit\Framework\TestCase;

class AccountTypeTest extends TestCase
{
    public function testCreateCheckingType(): void
    {
        $type = AccountType::checking();

        $this->assertEquals('CC', (string) $type);
    }

    public function testCreateSavingType(): void
    {
        $type = AccountType::saving();

        $this->assertEquals('PP', (string) $type);
    }

    public function testWhenCreateInvalidAccountTypeThenThrowException(): void
    {
        $this->expectException(InvalidAccountType::class);
        $this->expectExceptionMessage("Account type 'investment' is invalid");

        new AccountType('investment');
    }

    public function testIsChecking(): void
    {
        $this->assertTrue(AccountType::checking()->isChecking());
        $this->assertFalse(AccountType::saving()->isChecking());
    }
}
