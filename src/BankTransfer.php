<?php

declare(strict_types=1);

namespace Bradesco\Multipag;

use DateTimeImmutable;

class BankTransfer
{
    private string $transferId;
    private TransferAccount $accountHolder;
    private float $amount;
    private DateTimeImmutable $transferAt;

    public function __construct(
        string $transferId,
        float $amount,
        DateTimeImmutable $transferAt,
        TransferAccount $accountHolder
    ) {
        $this->amount = $amount;
        $this->transferAt = $transferAt;
        $this->accountHolder = $accountHolder;
        $this->transferId = $transferId;
    }

    public function getTransferId(): string
    {
        return $this->transferId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTransferAt(): DateTimeImmutable
    {
        return $this->transferAt;
    }

    public function getAccountHolder(): TransferAccount
    {
        return $this->accountHolder;
    }
}
