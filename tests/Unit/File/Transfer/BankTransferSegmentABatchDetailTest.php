<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit\File\Transfer;

use Bradesco\Multipag\AccountType;
use Bradesco\Multipag\Address;
use Bradesco\Multipag\BankDetails;
use Bradesco\Multipag\BankTransfer;
use Bradesco\Multipag\CNAB\Transfer\BankTransferSegmentABatchDetail;
use Bradesco\Multipag\TaxNumber;
use Bradesco\Multipag\TransferAccount;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BankTransferSegmentABatchDetailTest extends TestCase
{
    private const CLOCK_AT = '2021-04-05 10:57:05 UTC';

    public function testRenderDifferentBankTransferSegmentABatchDetail(): void
    {
        $batch = 1;
        $sequence = 1;
        $taxNumber = new TaxNumber('123.123.123-12');
        $address = new Address('Rua Uruguai', 122, 'Sala 3', 'Centro', 'Itajaí', '88302-200', 'SC');
        $bankDetails = new BankDetails('341', '3434-9', '5656565-X', AccountType::checking());
        $holder = new TransferAccount('ARTHUR DENT', $taxNumber, $address, $bankDetails);
        $transfer = new BankTransfer('78', 199.65, new DateTimeImmutable(self::CLOCK_AT), $holder);

        $line = BankTransferSegmentABatchDetail::render($transfer, $batch, $sequence, false);

        $this->assertEquals(240, strlen($line));
        $this->assertEquals(
            join('', [
                // Control
                '01.3A' => '237',
                '02.3A' => '0001',
                '03.3A' => '3',

                // Service
                '04.3A' => '00001',
                '05.3A' => 'A',
                '06.3A' => '0',
                '07.3A' => '00',

                // AccountHolder
                '08.3A' => '018',
                '09.3A' => '341',
                '10.3A' => '03434',
                '11.3A' => '9',
                '12.3A' => '000005656565',
                '13.3A' => 'X',
                '14.3A' => ' ',
                // >>
                '15.3A' => 'ARTHUR DENT                   ',

                // Payment
                '16.3A' => '00000000000000000078',
                '17.3A' => '05042021',
                '18.3A' => 'BRL',
                '19.3A' => '000000000000000',
                // >>
                '20.3A' => '000000000019965',
                '21.3A' => '                    ',
                '22.3A' => '00000000',
                '23.3A' => '000000000000000',

                // Information 2
                '24.3A' => '                                        ',
                '25.3A' => '  ',
                '26.3A' => '00010',
                // >>
                '27.3A' => 'CC',
                '28.3A' => '   ',
                '29.3A' => '0',
                '30.3A' => '          ',
            ]),
            $line,
        );
    }

    public function testRenderSameBankTransferSegmentABatchDetail(): void
    {
        $batch = 1;
        $sequence = 1;
        $taxNumber = new TaxNumber('123.123.123-12');
        $address = new Address('Rua Uruguai', 122, 'Sala 3', 'Centro', 'Itajaí', '88302-200', 'SC');
        $bankDetails = new BankDetails('341', '3434-9', '5656565-X', AccountType::checking());
        $holder = new TransferAccount('ARTHUR DENT', $taxNumber, $address, $bankDetails);
        $transfer = new BankTransfer('78', 199.65, new DateTimeImmutable(self::CLOCK_AT), $holder);

        $line = BankTransferSegmentABatchDetail::render($transfer, $batch, $sequence, true);

        $this->assertEquals(240, strlen($line));
        $this->assertEquals(
            join('', [
                // Control
                '01.3A' => '237',
                '02.3A' => '0001',
                '03.3A' => '3',

                // Service
                '04.3A' => '00001',
                '05.3A' => 'A',
                '06.3A' => '0',
                '07.3A' => '00',

                // AccountHolder
                '08.3A' => '000',
                '09.3A' => '341',
                '10.3A' => '03434',
                '11.3A' => '9',
                '12.3A' => '000005656565',
                '13.3A' => 'X',
                '14.3A' => ' ',
                // >>
                '15.3A' => 'ARTHUR DENT                   ',

                // Payment
                '16.3A' => '00000000000000000078',
                '17.3A' => '05042021',
                '18.3A' => 'BRL',
                '19.3A' => '000000000000000',
                // >>
                '20.3A' => '000000000019965',
                '21.3A' => '                    ',
                '22.3A' => '00000000',
                '23.3A' => '000000000000000',

                // Information 2
                '24.3A' => '                                        ',
                '25.3A' => '  ',
                '26.3A' => '     ',
                // >>
                '27.3A' => '  ',
                '28.3A' => '   ',
                '29.3A' => '0',
                '30.3A' => '          ',
            ]),
            $line,
        );
    }
}
