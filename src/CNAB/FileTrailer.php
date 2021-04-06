<?php

declare(strict_types=1);

namespace Bradesco\Multipag\CNAB;

class FileTrailer
{
    public static function render(int $numBatches, int $numEntries): string
    {
        /**
         * Number of entries also includes batch header and trailer
         */
        $totalEntries = $numEntries + ($numBatches * 2);

        return CNAB::join([
            // Control
            '01.9' => CNAB::G001_237_BRADESCO,
            '02.9' => CNAB::G002_9999_FILE_TRAILER,
            '03.9' => CNAB::G003_9_FILE_TRAILER,

            '04.9' => CNAB::alpha('', 9),

            '05.9' => CNAB::num($numBatches, 6),
            '06.9' => CNAB::num($totalEntries, 6),
            '07.9' => CNAB::num(0, 6),

            '08.0' => CNAB::alpha('', 205),
        ]);
    }
}
