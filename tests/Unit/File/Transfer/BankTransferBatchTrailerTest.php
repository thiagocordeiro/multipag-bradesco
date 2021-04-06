<?php

declare(strict_types=1);

namespace Test\Bradesco\Multipag\Unit\File\Transfer;

use Bradesco\Multipag\CNAB\CNAB;
use Bradesco\Multipag\CNAB\Transfer\BankTransferBatchTrailer;
use PHPUnit\Framework\TestCase;

class BankTransferBatchTrailerTest extends TestCase
{
    public function testRenderBatchHeader(): void
    {
        $batch = 1;
        $numRegisters = 7;
        $total = 15678.98;

        $header = BankTransferBatchTrailer::render($batch, $numRegisters, $total);

        $this->assertEquals(240, strlen($header));
        $this->assertEquals(
            join('', [
                // Control
                '01.5' => '237',
                '02.5' => '0001',
                '03.5' => '5',

                // Other
                '04.5' => '         ',

                // Totals
                '05.5' => '000009',
                '06.5' => '000000000001567898',
                '07.5' => '000000000000000000',

                // Other
                '08.5' => '000000',
                '09.5' => CNAB::alpha('', 165),
                '10.5' => '          ',
            ]),
            $header,
        );
    }
}
