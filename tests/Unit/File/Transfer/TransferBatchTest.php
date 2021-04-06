<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit\File\Transfer;

use Bradesco\Multipag\AccountType;
use Bradesco\Multipag\Address;
use Bradesco\Multipag\BankDetails;
use Bradesco\Multipag\CNAB\Transfer\TransferBatch;
use Bradesco\Multipag\TaxNumber;
use Bradesco\Multipag\TransferAccount;
use PHPUnit\Framework\TestCase;
use Test\Bradesco\Multipag\Unit\_Fixtures\BankTransferSpawner;

class TransferBatchTest extends TestCase
{
    private const FILE_PATH_SAME_BANK = __DIR__ . '/../../_Fixtures/batch-same-bank.txt';
    private const FILE_PATH_DIFFERENT_BANK = __DIR__ . '/../../_Fixtures/batch-different-bank.txt';

    public function testRenderTransferBatchSameBank(): void
    {
        $agreement = 123456;
        $batch = 4;
        $taxNumber = new TaxNumber('01.001.001/0001-01');
        $address = new Address('Rua Uruguai', 122, 'Sala 3', 'Centro', 'Itajaí', '88302-200', 'SC');
        $bankDetails = new BankDetails('237', '3434-9', '5656-7', AccountType::checking());
        $company = new TransferAccount('TCPAR HOLDING LTDA', $taxNumber, $address, $bankDetails);
        $transfer1 = BankTransferSpawner::getDarthVaderTransfer();

        $content = TransferBatch::render($agreement, $batch, false, $company, ...[$transfer1]);

        $this->assertStringEqualsFile(self::FILE_PATH_SAME_BANK, $content);
    }

    public function testRenderTransferBatchDifferentBank(): void
    {
        $agreement = 123456;
        $batch = 4;
        $taxNumber = new TaxNumber('01.001.001/0001-01');
        $address = new Address('Rua Uruguai', 122, 'Sala 3', 'Centro', 'Itajaí', '88302-200', 'SC');
        $bankDetails = new BankDetails('237', '3434-9', '5656-7', AccountType::checking());
        $company = new TransferAccount('TCPAR HOLDING LTDA', $taxNumber, $address, $bankDetails);
        $transfer1 = BankTransferSpawner::getFordArthurDentTransfer();
        $transfer2 = BankTransferSpawner::getFordPrefectTransfer();

        $content = TransferBatch::render($agreement, $batch, false, $company, ...[$transfer1, $transfer2]);

        $this->assertStringEqualsFile(self::FILE_PATH_DIFFERENT_BANK, $content);
    }
}
