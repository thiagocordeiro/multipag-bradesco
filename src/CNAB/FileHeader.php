<?php

declare(strict_types=1);

namespace Bradesco\Multipag\CNAB;

use Bradesco\Multipag\TransferAccount;
use DateTimeImmutable;

class FileHeader
{
    public static function render(
        int $sequence,
        int $agreement,
        TransferAccount $company,
        DateTimeImmutable $now
    ): string {
        $taxNumber = $company->getTaxNumber();
        $bankDetails = $company->getBankDetails();

        return CNAB::join([
            // Control
            '01.0' => CNAB::G001_237_BRADESCO,
            '02.0' => CNAB::G002_0000_FILE_HEADER,
            '03.0' => CNAB::G003_1_FILE_HEADER,

            '04.0' => CNAB::alpha('', 9),

            '05.0' => $taxNumber->getType(),
            '06.0' => CNAB::num($taxNumber->getNumber(), 14),
            '07.0' => CNAB::alpha($agreement, 20),
            '08.0' => CNAB::num($bankDetails->getBranchCode(), 5),
            '09.0' => CNAB::num($bankDetails->getBranchCodeDigit(), 1),
            '10.0' => CNAB::num($bankDetails->getAccountNumber(), 12),
            '11.0' => CNAB::num($bankDetails->getAccountNumberDigit(), 1),
            '12.0' => ' ',
            '13.0' => CNAB::alpha($company->getName(), 30),

            '14.0' => CNAB::alpha('BANCO BRADESCO', 30),
            '15.0' => CNAB::alpha('', 10),

            '16.0' => CNAB::G015_1_REMITTANCE,
            '17.0' => $now->format('dmY'),
            '18.0' => $now->format('his'),
            '19.0' => CNAB::num($sequence, 6),
            '20.0' => CNAB::G019_089_FILE_LAYOUT_VERSION,
            '21.0' => CNAB::G020_01600_FILE_DENSITY,

            '22.0' => CNAB::alpha('', 20),
            '23.0' => CNAB::alpha('', 20),
            '24.0' => CNAB::alpha('', 29),
        ]);
    }
}
