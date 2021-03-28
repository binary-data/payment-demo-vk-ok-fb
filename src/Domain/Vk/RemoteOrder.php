<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Domain\Vk;

final class RemoteOrder
{
    public function __construct(
        private string $id,
        private string $status,
        private string $item,
        private string $amount,
        private string $date
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getItem(): string
    {
        return $this->item;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}
