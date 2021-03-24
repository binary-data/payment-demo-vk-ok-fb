<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Model;

use Binarydata\PaymentDemo\Domain\Order;
use Binarydata\PaymentDemo\Domain\OrderRepositoryInterface;
use Cycle\ORM\ORM;
use Cycle\ORM\Transaction;

final class CycleOrderRepository implements OrderRepositoryInterface
{
    public function __construct(private ORM $orm) {}

    public function save(Order $order): int
    {
        (new Transaction($this->orm))->persist($order)->run();

        return $order->getId();
    }

    public function findByExternalId(int $externalId): ?Order
    {
        /** @var Order|null $order */
        $order = $this->orm->getRepository(Order::class)->findOne(['externalId' => $externalId]);

        return $order;
    }
}
