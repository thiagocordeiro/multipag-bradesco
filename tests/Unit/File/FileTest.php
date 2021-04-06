<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit\File;

use Bradesco\Multipag\AccountType;
use Bradesco\Multipag\Address;
use Bradesco\Multipag\BankDetails;
use Bradesco\Multipag\BankTransfer;
use Bradesco\Multipag\CNAB\File;
use Bradesco\Multipag\TaxNumber;
use Bradesco\Multipag\TransferAccount;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    private const CLOCK_AT = '2021-04-05 10:57:05 UTC';
    private const FILE_WITHOUT_TRANSACTIONS = __DIR__ . '/../_Fixtures/empty-remittance.txt';
    private const FILE_WITH_TRANSACTIONS = __DIR__ . '/../_Fixtures/remittance.txt';

    public function testRenderEmptyFile(): void
    {
        $sequence = 1;
        $agreement = 123456;
        $taxNumber = new TaxNumber('01.001.001/0001-01');
        $address = new Address('Rua Uruguai', 122, '', 'Centro', 'Itajaí', '88302-200', 'SC');
        $bankDetails = new BankDetails('237', '3456-7', '0012312-3', AccountType::checking());
        $company = new TransferAccount('REMESSA BRADESCO LTDA', $taxNumber, $address, $bankDetails);
        $now = new DateTimeImmutable(self::CLOCK_AT);

        $content = File::render($sequence, $agreement, $company, $now, [], []);

        $this->assertStringEqualsFile(self::FILE_WITHOUT_TRANSACTIONS, $content);
    }

    public function testRenderFileWithTransactions(): void
    {
        $sequence = 1;
        $agreement = 123456;
        $taxNumber = new TaxNumber('01.001.001/0001-01');
        $address = new Address('Rua Uruguai', 122, '', 'Centro', 'Itajaí', '88302-200', 'SC');
        $bankDetails = new BankDetails('237', '3456-7', '0012312-3', AccountType::checking());
        $company = new TransferAccount('REMESSA BRADESCO LTDA', $taxNumber, $address, $bankDetails);
        $now = new DateTimeImmutable(self::CLOCK_AT);

        $sameBankTransfers = [
            $this->testGetTransferBradesco(12),
        ];
        $otherBankTransfers = [
            $this->testGetTransferBancoDoBrasil(5),
            $this->testGetTransferItau(8),
            $this->testCitiBank(12),
            $this->testSantander(13),
            $this->testGetTransferItauPf(15),
        ];

        $content = File::render($sequence, $agreement, $company, $now, $sameBankTransfers, $otherBankTransfers);

        $this->assertStringEqualsFile(self::FILE_WITH_TRANSACTIONS, $content);
    }

    private function testGetTransferBancoDoBrasil(int $id): BankTransfer
    {
        $taxNumber = new TaxNumber('346.154.340-30');
        $address = new Address('Rua Central de Garuva', 876, '', 'Centro', 'Garuva', '89248-000', 'SC');
        $bankDetails = new BankDetails('001', '4648', '19610-X', AccountType::checking());
        $account = new TransferAccount('ARTHUR DENT', $taxNumber, $address, $bankDetails);

        return new BankTransfer("{$id}", 4356.12, new DateTimeImmutable(self::CLOCK_AT), $account);
    }

    private function testGetTransferItau(int $id): BankTransfer
    {
        $taxNumber = new TaxNumber('01.001.001/0001-01');
        $address = new Address('Rua Uruguai', 122, 'Sala 3', 'Centro', 'Itajaí', '88302-200', 'SC');
        $bankDetails = new BankDetails('341', '0292', '99887-9', AccountType::checking());
        $account = new TransferAccount('TCPAR HOLDING LTDA', $taxNumber, $address, $bankDetails);

        return new BankTransfer("{$id}", 1242.99, new DateTimeImmutable(self::CLOCK_AT), $account);
    }

    private function testGetTransferBradesco(int $id): BankTransfer
    {
        $taxNumber = new TaxNumber('974.941.740-28');
        $address = new Address('Presidente Itamar Franco', 445, '', 'Centro', 'Juiz De Fora', '36010-021', 'MG');
        $bankDetails = new BankDetails('237', '3442', '0948828-6', AccountType::checking());
        $account = new TransferAccount('JOHN TRAVOLTA', $taxNumber, $address, $bankDetails);

        return new BankTransfer("{$id}", 45132.66, new DateTimeImmutable(self::CLOCK_AT), $account);
    }

    private function testCitiBank(int $id): BankTransfer
    {
        $taxNumber = new TaxNumber('974.941.740-28');
        $address = new Address('Av. Brg. Faria Lima', 3600, '10 andar', 'Itaim Bibi', 'São Paulo', '04538-132', 'SP');
        $bankDetails = new BankDetails('745', '0061', '1067158918-0', AccountType::checking());
        $account = new TransferAccount('KEVIN BACON', $taxNumber, $address, $bankDetails);

        return new BankTransfer("{$id}", 5451.19, new DateTimeImmutable(self::CLOCK_AT), $account);
    }

    private function testSantander(int $id): BankTransfer
    {
        $taxNumber = new TaxNumber('201.313.090-21');
        $address = new Address('Rua Carlos Feter', 569, '', 'Centro', 'Farroupilha', '95180-975', 'RS');
        $bankDetails = new BankDetails('033', '1094', '03972405-6', AccountType::checking());
        $account = new TransferAccount('RAUL SEIXAS', $taxNumber, $address, $bankDetails);

        return new BankTransfer("{$id}", 98134.98, new DateTimeImmutable(self::CLOCK_AT), $account);
    }

    private function testGetTransferItauPf(int $id): BankTransfer
    {
        $taxNumber = new TaxNumber('22781375900');
        $address = new Address('Maestro Osvaldo Hohmann', 808, '', 'Santa Felicidade', 'Curitiba', '82020-774', 'SC');
        $bankDetails = new BankDetails('341', '5520', '75835-9', AccountType::checking());
        $account = new TransferAccount('CHUCK NORRIS', $taxNumber, $address, $bankDetails);

        return new BankTransfer("{$id}", 3541.56, new DateTimeImmutable(self::CLOCK_AT), $account);
    }
}
