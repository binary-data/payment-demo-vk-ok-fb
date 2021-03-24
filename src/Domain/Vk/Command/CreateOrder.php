<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Domain\Vk\Command;

use Binarydata\PaymentDemo\Domain\Order;
use Binarydata\PaymentDemo\Domain\OrderRepositoryInterface;
use Binarydata\PaymentDemo\Domain\SocialType;

final class CreateOrder
{
    public function __construct(private OrderRepositoryInterface $orderRepository) {}

    public function create(string $userId, int $externalId): int
    {
        $order = new Order(
            SocialType::VK,
            $userId,
            $externalId
        );

        return $this->orderRepository->save($order);
    }
}
