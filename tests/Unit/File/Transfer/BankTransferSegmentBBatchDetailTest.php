<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit\File\Transfer;

use Bradesco\Multipag\AccountType;
use Bradesco\Multipag\Address;
use Bradesco\Multipag\BankDetails;
use Bradesco\Multipag\BankTransfer;
use Bradesco\Multipag\CNAB\Transfer\BankTransferSegmentBBatchDetail;
use Bradesco\Multipag\TaxNumber;
use Bradesco\Multipag\TransferAccount;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BankTransferSegmentBBatchDetailTest extends TestCase
{
    private const CLOCK_AT = '2021-04-05 10:57:05 UTC';

    public function testRenderBankTransferSegmentBBatchDetailTest(): void
    {
        $batch = 1;
        $sequence = 1;
        $taxNumber = new TaxNumber('123.123.123-12');
        $address = new Address('Rua Uruguai', 122, 'Sala 3', 'Centro', 'Itajaí', '88302-200', 'SC');
        $bankDetails = new BankDetails('341', '3434-9', '5656565-X', AccountType::checking());
        $holder = new TransferAccount('ARTHUR DENT', $taxNumber, $address, $bankDetails);
        $transfer = new BankTransfer('78', 199.65, new DateTimeImmutable(self::CLOCK_AT), $holder);

        $line = BankTransferSegmentBBatchDetail::render($transfer, $batch, $sequence);

        $this->assertEquals(240, strlen($line));
        $this->assertEquals(
            join('', [
                // Control
                '01.3B' => '237',
                '02.3B' => '0001',
                '03.3B' => '3',

                // Service
                '04.3B' => '00001',
                '05.3B' => 'B',
                '06.3B' => '   ',

                // Additional information: AccountHolder
                '07.3B' => '1',
                '08.3B' => '00012312312312',
                '09.3B' => 'RUA URUGUAI                   ',
                '10.3B' => '00122',
                '11.3B' => 'SALA 3         ',
                '12.3B' => 'CENTRO         ',
                '13.3B' => 'ITAJAI              ',
                '14.3B + 15.3B' => '88302200',
                '16.3B' => 'SC',

                // Additional information: Payment
                '17.3B' => '05042021',
                '18.3B' => '000000000019965',
                '19.3B' => '000000000000000',
                '20.3B' => '000000000000000',
                '21.3B' => '000000000000000',
                '22.3B' => '000000000000000',
                '23.3B' => '               ',

                '24.3B' => '0',
                '25.3B' => '000000',
                '26.3B' => '00000000',
            ]),
            $line,
        );
    }
}
