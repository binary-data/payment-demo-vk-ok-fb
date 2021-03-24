<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Middleware;

use Binarydata\PaymentDemo\Action\VkPaymentAction;
use Binarydata\PaymentDemo\Domain\Order;
use Binarydata\PaymentDemo\Domain\Vk\VkItem;
use Binarydata\Shpongle\Http\RequestAttribute;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class VkResponseMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $actionVars = $request->getAttribute(RequestAttribute::RESPONSE_VARS);
        $type = $actionVars['type'] ?? null;

        if ($type === VkPaymentAction::TYPE_ITEM) {
            /** @var VkItem $item */
            $item = $actionVars['item'];

            return $this->createResponse([
                'response' => [
                    'title' => $item->getTitle(),
                    'photo_url' => $item->getPhotoUrl(),
                    'price' => $item->getPrice(),
                    'item_id' => $item->getId(),
                    'expiration' => $item->getExpiration(),
                ],
            ]);
        }

        if ($type === VkPaymentAction::TYPE_PAYMENT) {
            /** @var Order $order */
            $order = $actionVars['order'];

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
