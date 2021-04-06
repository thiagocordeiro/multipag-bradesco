<?php

declare(strict_types=1);

namespace Bradesco\Multipag\CNAB\Transfer;

use Bradesco\Multipag\CNAB\CNAB;

class BankTransferBatchTrailer
{
    public static function render(int $batch, int $numRegisters, float $total): string
    {
        /**
         * Header and Trailer should also be included as number of Entries
         */
        $totalEntries = $numRegisters + 2;

        return CNAB::join([
            // Control
            '01.5' => CNAB::G001_237_BRADESCO,
            '02.5' => CNAB::num($batch, 4),
            '03.5' => CNAB::G003_5_BATCH_TRAILER,

            // Other
            '04.5' => '         ',

            // Totals
            '05.5' => CNAB::num($totalEntries, 6),
            '06.5' => CNAB::value($total, 18),
            '07.5' => '000000000000000000',

            // Other
            '08.5' => '000000',
            '09.5' => CNAB::alpha('', 165),
            '10.5' => '          ',
        ]);
    }
}
