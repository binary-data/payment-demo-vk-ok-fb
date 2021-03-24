<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Domain;

interface OrderRepositoryInterface
{
    public function save(Order $order): int;

    public function findByExternalId(int $externalId): ?Order;
}
