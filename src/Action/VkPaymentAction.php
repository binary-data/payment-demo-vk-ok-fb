<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Action;

use Binarydata\PaymentDemo\Domain\Vk\Command\CompleteOrder;
use Binarydata\PaymentDemo\Domain\Vk\Command\CreateOrder;
use Binarydata\PaymentDemo\Domain\Vk\VkItem;
use Binarydata\Shpongle\Http\BaseAction;
use DateTimeImmutable;
use Psr\Http\Message\ServerRequestInterface;

final class VkPaymentAction extends BaseAction
{
    public const TYPE_ITEM = 1,
        TYPE_PAYMENT = 2
    ;

    public function __construct(private CreateOrder $createOrder, private CompleteOrder $completeOrder) {}

    public function getResponseVars(ServerRequestInterface $request): array
    {
        $params = $request->getParsedBody();
        $type = $params['notification_type'] ?? null;

        if ($type === 'get_item' || $type === 'get_item_test') {
            $this->createOrder->create($params['user_id'], (int) $params['order_id']);

            return [
                'type' => self::TYPE_ITEM,
                'item' => new VkItem('Awesome pack', 'https://robohash.org/awesomePack.png?size=75x75', 19, 'awesome_1')
            ];
        }

        $status = $params['status'] ?? null;

        if ($status === 'chargeable' && $this->isOrderStatusChange($type)) {
            return [
                'type' => self::TYPE_PAYMENT,
                'order' => $this->completeOrder->complete(
                    (int) $params['order_id'],
                    (new DateTimeImmutable())->setTimestamp((int) $params['date']),
                    (float) $params['item_price']
                ),
            ];
        }

        return [];
    }

    private function isOrderStatusChange(string $type): bool
    {
        return $type === 'order_status_change' || $type === 'order_status_change_test';
    }
}
