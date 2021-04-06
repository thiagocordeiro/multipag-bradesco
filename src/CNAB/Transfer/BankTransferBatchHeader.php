<?php

declare(strict_types=1);

namespace Bradesco\Multipag\CNAB\Transfer;

use Bradesco\Multipag\CNAB\CNAB;
use Bradesco\Multipag\TransferAccount;

class BankTransferBatchHeader
{
    public static function render(int $agreement, int $batch, bool $sameBank, TransferAccount $company): string
    {
        $taxNumber = $company->getTaxNumber();
        $bankDetails = $company->getBankDetails();
        $address = $company->getAddress();
        $entryType = $sameBank ?
            CNAB::G029_01_ENTRY_TYPE_ACCOUNT_CREDIT :
            CNAB::G029_03_ENTRY_TYPE_DOC_TED;

        return CNAB::join([
            // Control
            '01.1' => CNAB::G001_237_BRADESCO,
            '02.1' => CNAB::num($batch, 4),
            '03.1' => CNAB::G003_1_BATCH_HEADER,

            // Service
            '04.1' => CNAB::G028_C_OPERATION_TYPE,
            '05.1' => CNAB::G025_98_SERVICE_TYPE_OTHERS,
            '06.1' => $entryType,
            '07.1' => CNAB::G030_045_LAYOUT_VERSION,

            // Other
            '08.1' => ' ',

            // Company
            '09.1' => $taxNumber->getType(),
            '10.1' => CNAB::num($taxNumber->getNumber(), 14),
            '11.1' => CNAB::alpha($agreement, 20),
            '12.1' => CNAB::num($bankDetails->getBranchCode(), 5),
            '13.1' => CNAB::num($bankDetails->getBranchCodeDigit(), 1),
            '14.1' => CNAB::num($bankDetails->getAccountNumber(), 12),
            '15.1' => CNAB::num($bankDetails->getAccountNumberDigit(), 1),
            '16.1' => ' ',
            '17.1' => CNAB::alpha($company->getName(), 30),

            // Information 1
            '18.1' => '                                        ',

            // Company Address
            '19.1' => CNAB::alpha($address->getStreet(), 30),
            '20.1' => CNAB::num($address->getNumber(), 5),
            '21.1' => CNAB::alpha($address->getAdditionalDetails(), 15),
            '22.1' => CNAB::alpha($address->getCity(), 20),
            '23.1 + 24.1' => CNAB::alpha($address->getPostalCode(), 8),
            '25.1' => $address->getState(),

            // Other
            '26.1' => CNAB::P014_01_PAYMENT_METHOD,
            '27.1' => '      ',
            '28.1' => '          ',
        ]);
    }
}
