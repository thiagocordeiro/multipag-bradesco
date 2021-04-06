<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit\File;

use Bradesco\Multipag\AccountType;
use Bradesco\Multipag\Address;
use Bradesco\Multipag\BankDetails;
use Bradesco\Multipag\CNAB\FileHeader;
use Bradesco\Multipag\TaxNumber;
use Bradesco\Multipag\TransferAccount;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class FileHeaderTest extends TestCase
{
    private const CLOCK_AT = '2021-04-05 10:57:05 UTC';

    public function testRenderFileHeader(): void
    {
        $sequence = 15;
        $agreement = 123456;
        $taxNumber = new TaxNumber('01.001.001/0001-01');
        $address = new Address('Rua Uruguai', 122, 'Sala 3', 'Centro', 'Itajaí', '88302-200', 'SC');
        $bankDetails = new BankDetails('237', '3434-9', '12332-1', AccountType::checking());
        $company = new TransferAccount('TCPAR HOLDING LTDA', $taxNumber, $address, $bankDetails);

        $header = FileHeader::render($sequence, $agreement, $company, new DateTimeImmutable(self::CLOCK_AT));

        $this->assertEquals(240, strlen($header));
        $this->assertEquals(
            join('', [
                // Control
                '01.0' => '237',
                '02.0' => '0000',
                '03.0' => '0',

                '04.0' => '         ',

                '05.0' => '2',
                '06.0' => '01001001000101',
                '07.0' => '123456              ',
                '08.0 + 09.0' => '034349',
                '10.0 + 11.0' => '0000000123321',
                '12.0' => ' ',
                '13.0' => 'TCPAR HOLDING LTDA            ',

                '14.0' => 'BANCO BRADESCO                ',
                '15.0' => '          ',

                '16.0' => '1',
                '17.0' => '05042021',
                '18.0' => '105705',
                '19.0' => '000015',
                '20.0' => '089',
                '21.0' => '01600',

                '22.0' => '                    ',
                '23.0' => '                    ',
                '24.0' => '                             ',
            ]),
            $header,
        );
    }
}
