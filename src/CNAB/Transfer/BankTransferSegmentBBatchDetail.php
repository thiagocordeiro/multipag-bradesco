<?php

declare(strict_types=1);

namespace Bradesco\Multipag\CNAB\Transfer;

use Bradesco\Multipag\BankTransfer;
use Bradesco\Multipag\CNAB\CNAB;

class BankTransferSegmentBBatchDetail
{
    public static function render(BankTransfer $transfer, int $batch, int $sequence): string
    {
        $accountHolder = $transfer->getAccountHolder();
        $taxNumber = $accountHolder->getTaxNumber();
        $address = $accountHolder->getAddress();
        $payAt = $transfer->getTransferAt();
        $amount = $transfer->getAmount();

        return CNAB::join([
            // Control
            '01.3B' => CNAB::G001_237_BRADESCO,
            '02.3B' => CNAB::num($batch, 4),
            '03.3B' => CNAB::G003_3_REGISTER_TYPE_DETAIL,

            // Service
            '04.3B' => CNAB::num($sequence, 5),
            '05.3B' => CNAB::G039_SEGMENT_TYPE_B,
            '06.3B' => '   ',

            // Additional information: AccountHolder
            '07.3B' => $taxNumber->getType(),
            '08.3B' => CNAB::num($taxNumber->getNumber(), 14),
            '09.3B' => CNAB::alpha($address->getStreet(), 30),
            '10.3B' => CNAB::num($address->getNumber(), 5),
            '11.3B' => CNAB::alpha($address->getAdditionalDetails(), 15),
            '12.3B' => CNAB::alpha($address->getNeighborhood(), 15),
            '13.3B' => CNAB::alpha($address->getCity(), 20),
            '14.3B + 15.3B' => CNAB::alpha($address->getPostalCode(), 8),
            '16.3B' => $address->getState(),

            // Additional information: Payment
            '17.3B' => $payAt->format('dmY'),
            '18.3B' => CNAB::value($amount, 15),
            '19.3B' => '000000000000000',
            '20.3B' => '000000000000000',
            '21.3B' => '000000000000000',
            '22.3B' => '000000000000000',
            '23.3B' => '               ',

            '24.3B' => CNAB::P006_0_DO_NOT_NOTIFY,
            '25.3B' => '000000',
            '26.3B' => '00000000',
        ]);
    }
}
