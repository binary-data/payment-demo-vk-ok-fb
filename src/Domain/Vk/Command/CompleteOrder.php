<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Domain\Vk\Command;

use Binarydata\PaymentDemo\Domain\Order;
use Binarydata\PaymentDemo\Domain\OrderRepositoryInterface;
use Binarydata\PaymentDemo\Domain\OrderStatus;
use DateTimeImmutable;
use RuntimeException;

final class CompleteOrder
{
    public function __construct(private OrderRepositoryInterface $orderRepository) {}

    public function complete(int $externalId, DateTimeImmutable $externalDate, float $price): Order
    {
        $order = $this->orderRepository->findByExternalId($externalId);

        if ($order === null) {
            throw new RuntimeException("order with external id $externalId not found");
        }

        $order->setStatus(OrderStatus::COMPLETED);
        $order->setExternalDate($externalDate);
        $order->setTotal($price);

        $this->orderRepository->save($order);

        return $order;
    }
}
