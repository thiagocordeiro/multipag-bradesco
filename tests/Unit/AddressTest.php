<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit;

use Bradesco\Multipag\Address;
use Bradesco\Multipag\Exception\InvalidAddress;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testInvalidState(): void
    {
        $state = 'Santa Catarina';

        $this->expectException(InvalidAddress::class);
        $this->expectExceptionMessage("State 'Santa Catarina' is invalid. The value must contain exactly 2 digits");

        new Address('Rua Uruguai', 122, 'Sala 3', 'Centro', 'Itajaí', '88302-200', $state);
    }

    public function testInvalidPostalCode(): void
    {
        $postalCode = '88302';

        $this->expectException(InvalidAddress::class);
        $this->expectExceptionMessage("Postal code '88302' is invalid. The value must contain exactly 8 digits");

        new Address('Rua Uruguai', 122, 'Sala 3', 'Centro', 'Itajaí', $postalCode, 'SC');
    }
}
