<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit\File\Transfer;

use Bradesco\Multipag\AccountType;
use Bradesco\Multipag\Address;
use Bradesco\Multipag\BankDetails;
use Bradesco\Multipag\CNAB\Transfer\BankTransferBatchHeader;
use Bradesco\Multipag\TaxNumber;
use Bradesco\Multipag\TransferAccount;
use PHPUnit\Framework\TestCase;

class BankTransferBatchHeaderTest extends TestCase
{
    private TransferAccount $company;

    protected function setUp(): void
    {
        $this->company = new TransferAccount(
            'TCPAR HOLDING LTDA',
            new TaxNumber('01.001.001/0001-01'),
            new Address('Rua Uruguai', 122, 'Sala 3', 'Centro', 'Itajaí', '88302-200', 'SC'),
            new BankDetails('237', '3434-9', '5656-7', AccountType::checking()),
        );
    }

    public function testRenderBatchHeaderForSameBank(): void
    {
        $batch = 1;
        $agreement = 123456;

        $line = BankTransferBatchHeader::render($agreement, $batch, true, $this->company);

        $this->assertEquals(240, strlen($line));
        $this->assertEquals(
            join('', [
                // Control
                '01.1' => '237',
                '02.1' => '0001',
                '03.1' => '1',

                // Service
                '04.1' => 'C',
                '05.1' => '70',
                '06.1' => '01',
                '07.1' => '045',

                // Other
                '08.1' => ' ',

                // Company
                '09.1' => '2',
                '10.1' => '01001001000101',
                '11.1' => '123456              ',
                '12.1' => '03434',
                '13.1' => '9',
                '14.1' => '000000005656',
                '15.1' => '7',
                '16.1' => ' ',
                '17.1' => 'TCPAR HOLDING LTDA            ',

                // Information 1
                '18.1' => '                                        ',

                // Company Address
                '19.1' => 'RUA URUGUAI                   ',
                '20.1' => '00122',
                '21.1' => 'SALA 3         ',
                '22.1' => 'ITAJAI              ',
                '23.1 + 24.1' => '88302200',
                '25.1' => 'SC',

                // Other
                '26.1' => '01',
                '27.1' => '      ',
                '28.1' => '          ',
            ]),
            $line,
        );
    }

    public function testRenderBatchHeaderForDifferentBank(): void
    {
        $batch = 1;
        $agreement = 123456;

        $line = BankTransferBatchHeader::render($agreement, $batch, false, $this->company);

        $this->assertEquals(240, strlen($line));
        $this->assertEquals(
            join('', [
                // Control
                '01.1' => '237',
                '02.1' => '0001',
                '03.1' => '1',

                // Service
                '04.1' => 'C',
                '05.1' => '70',
                '06.1' => '03',
                '07.1' => '045',

                // Other
                '08.1' => ' ',

                // Company
                '09.1' => '2',
                '10.1' => '01001001000101',
                '11.1' => '123456              ',
                '12.1' => '03434',
                '13.1' => '9',
                '14.1' => '000000005656',
                '15.1' => '7',
                '16.1' => ' ',
                '17.1' => 'TCPAR HOLDING LTDA            ',

                // Information 1
                '18.1' => '                                        ',

                // Company Address
                '19.1' => 'RUA URUGUAI                   ',
                '20.1' => '00122',
                '21.1' => 'SALA 3         ',
                '22.1' => 'ITAJAI              ',
                '23.1 + 24.1' => '88302200',
                '25.1' => 'SC',

                // Other
                '26.1' => '01',
                '27.1' => '      ',
                '28.1' => '          ',
            ]),
            $line,
        );
    }
}
