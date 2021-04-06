<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit\_Fixtures;

use Bradesco\Multipag\AccountType;
use Bradesco\Multipag\Address;
use Bradesco\Multipag\BankDetails;
use Bradesco\Multipag\BankTransfer;
use Bradesco\Multipag\TaxNumber;
use Bradesco\Multipag\TransferAccount;
use DateTimeImmutable;

class BankTransferSpawner
{
    private const CLOCK_AT = '2021-04-05 10:57:05 UTC';

    public static function getFordArthurDentTransfer(): BankTransfer
    {
        $taxNumber = new TaxNumber('567.567.567-56');
        $address = new Address('Av. Armação ', 8987, '', 'Centro', 'Navegantes', '88370-300', 'SC');
        $bankDetails = new BankDetails('001', '8981', '667766-2', AccountType::checking());
        $holder = new TransferAccount('ARTHUR DENT', $taxNumber, $address, $bankDetails);

        return new BankTransfer('78', 5643.32, new DateTimeImmutable(self::CLOCK_AT), $holder);
    }

    public static function getFordPrefectTransfer(): BankTransfer
    {
        $taxNumber = new TaxNumber('123.123.123-12');
        $address = new Address('Av. Atlantica', 2345, '', 'Barra Sul', 'Balneario Camboriu', '88990-123', 'SC');
        $bankDetails = new BankDetails('077', '0001', '123456-7', AccountType::checking());
        $holder = new TransferAccount('FORD PREFECT', $taxNumber, $address, $bankDetails);

        return new BankTransfer('78', 10323.99, new DateTimeImmutable(self::CLOCK_AT), $holder);
    }

    public static function getDarthVaderTransfer(): BankTransfer
    {
        $taxNumber = new TaxNumber('123.123.123-12');
        $address = new Address('Rod. Osvaldo Reis', 2345, '', 'Fazenda', 'Itajaí', '88990-123', 'SC');
        $bankDetails = new BankDetails('237', '4545-4', '565656-5', AccountType::checking());
        $holder = new TransferAccount('DARTH VADER', $taxNumber, $address, $bankDetails);

        return new BankTransfer('78', 767.34, new DateTimeImmutable(self::CLOCK_AT), $holder);
    }
}
