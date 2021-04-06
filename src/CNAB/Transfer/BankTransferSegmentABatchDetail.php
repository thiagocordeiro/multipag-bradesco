<?php

declare(strict_types=1);

namespace Bradesco\Multipag\CNAB\Transfer;

use Bradesco\Multipag\BankTransfer;
use Bradesco\Multipag\CNAB\CNAB;

class BankTransferSegmentABatchDetail
{
    public static function render(BankTransfer $transfer, int $batch, int $sequence, bool $sameBank): string
    {
        $transferId = $transfer->getTransferId();
        $accountHolder = $transfer->getAccountHolder();
        $bankDetails = $accountHolder->getBankDetails();
        $payAt = $transfer->getTransferAt();
        $amount = $transfer->getAmount();
        $compensationChamber = $sameBank ? '000' : CNAB::P001_COMPENSATION_CHAMBER_TED;
        $tedReason = $sameBank ? '     ' : CNAB::P011_00010_TRANSFER_REASON_ACCOUNT_CREDIT;
        $tedReasonRemark = $sameBank ? '  ' : $bankDetails->getAccountType();

        return CNAB::join([
            // Control
            '01.3A' => CNAB::G001_237_BRADESCO,
            '02.3A' => CNAB::num($batch, 4),
            '03.3A' => CNAB::G003_3_REGISTER_TYPE_DETAIL,

            // Service
            '04.3A' => CNAB::num($sequence, 5),
            '05.3A' => CNAB::G039_SEGMENT_TYPE_A,
            '06.3A' => CNAB::G060_0_MOVEMENT_TYPE_INCLUDE,
            '07.3A' => CNAB::G061_00_MOVEMENT_CODE_INCLUDE_RELEASED,

            // AccountHolder
            '08.3A' => $compensationChamber,
            '09.3A' => $bankDetails->getBankCode(),
            '10.3A' => CNAB::num($bankDetails->getBranchCode(), 5),
            '11.3A' => CNAB::num($bankDetails->getBranchCodeDigit(), 1),
            '12.3A' => CNAB::num($bankDetails->getAccountNumber(), 12),
            '13.3A' => CNAB::alpha($bankDetails->getAccountNumberDigit(), 1),
            '14.3A' => ' ',
            '15.3A' => CNAB::alpha($accountHolder->getName(), 30),

            // Payment
            '16.3A' => CNAB::num($transferId, 20),
            '17.3A' => $payAt->format('dmY'),
            '18.3A' => CNAB::G040_BRL_CURRENCY_TYPE,
            '19.3A' => '000000000000000',
            '20.3A' => CNAB::value($amount, 15),
            '21.3A' => '                    ',
            '22.3A' => '00000000',
            '23.3A' => '000000000000000',

            // Information 2
            '24.3A' => '                                        ',
            '25.3A' => '  ',
            '26.3A' => $tedReason,
            '27.3A' => $tedReasonRemark,
            '28.3A' => '   ',
            '29.3A' => CNAB::P006_0_DO_NOT_NOTIFY,
            '30.3A' => '          ',
        ]);
    }
}
