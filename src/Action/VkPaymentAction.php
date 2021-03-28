<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Action;

use Binarydata\PaymentDemo\Domain\Vk\Command\CompleteOrder;
use Binarydata\PaymentDemo\Domain\Vk\Command\CreateOrder;
use Binarydata\PaymentDemo\Domain\Vk\RemoteOrderRepositoryInterface;
use DateTimeImmutable;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class VkPaymentAction implements MiddlewareInterface
{
    public function __construct(
        private CreateOrder $createOrder,
        private CompleteOrder $completeOrder,
        private RemoteOrderRepositoryInterface $remoteOrderRepository,
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getParsedBody();
        $type = $params['notification_type'] ?? null;

        if ($type === 'get_item' || $type === 'get_item_test') {
            $this->createOrder->create($params['user_id'], (int) $params['order_id']);

            return $this->createResponse([
                'response' => [
                    'title' => 'Awesome pack',
                    'photo_url' => 'https://robohash.org/awesomePack.png?size=75x75',
                    'price' => 19,
                    'item_id' => 'awesome_1',
                    'expiration' => 0,
                ],
            ]);
        }

        $status = $params['status'] ?? null;

        if ($status === 'chargeable' && $this->isOrderStatusChange($type)) {
            $order = $this->completeOrder->complete(
                (int) $params['order_id'],
                (new DateTimeImmutable())->setTimestamp((int) $params['date']),
                (float) $params['item_price']
            );

            return $this->createResponse([
                'response' => [
                    'order_id' => $order->getExternalId(),
                    'app_order_id' => $order->getId(),
                ],
            ]);
        }

        return $this->createResponse([
            'error' => [
                'error_code' => 1,
                'error_msg' => 'something went wrong',
                'critical' => true,
            ],
        ]);
    }

    private function isOrderStatusChange(string $type): bool
    {
        return $type === 'order_status_change' || $type === 'order_status_change_test';
    }

    private function createResponse(array $data): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse()
            ->withHeader('Content-Type', 'application/json')
            ->withBody(
                $this->streamFactory->createStream(json_encode($data, JSON_THROW_ON_ERROR))
            );
    }
}
