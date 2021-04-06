<?php

declare(strict_types=1);

namespace Bradesco\Multipag\CNAB\Transfer;

use Bradesco\Multipag\BankTransfer;
use Bradesco\Multipag\CNAB\CNAB;
use Bradesco\Multipag\CNAB\Transfer\BankTransferBatchHeader as Header;
use Bradesco\Multipag\CNAB\Transfer\BankTransferBatchTrailer as Trailer;
use Bradesco\Multipag\CNAB\Transfer\BankTransferSegmentABatchDetail as SegmentA;
use Bradesco\Multipag\CNAB\Transfer\BankTransferSegmentBBatchDetail as SegmentB;
use Bradesco\Multipag\TransferAccount;

class TransferBatch
{
    public static function render(
        int $agreement,
        int $batch,
        bool $sameBank,
        TransferAccount $company,
        BankTransfer ...$transfers
    ): string {
        $lines = [Header::render($agreement, $batch, $sameBank, $company)];
        $sequence = 0;
        $total = 0;

        foreach ($transfers as $transfer) {
            $total += $transfer->getAmount();
            $lines[] = SegmentA::render($transfer, $batch, ++$sequence, $sameBank);
            $lines[] = SegmentB::render($transfer, $batch, ++$sequence);
        }

        $lines[] = Trailer::render($batch, $sequence, $total);

        return CNAB::join($lines, "\r\n");
    }
}
