<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Action;

use Binarydata\PaymentDemo\Domain\Vk\RemoteOrder;
use Binarydata\PaymentDemo\Domain\Vk\RemoteOrderRepositoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetOrdersAction implements MiddlewareInterface
{
    public function __construct(
        private RemoteOrderRepositoryInterface $remoteOrderRepository,
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'application/json')
            ->withBody(
                $this->streamFactory->createStream(json_encode([
                    'orders' => collect($this->remoteOrderRepository->findAll())
                        ->map(fn(RemoteOrder $ro) => [
                            'id' => $ro->getId(),
                            'status' => $ro->getStatus(),
                            'item' => $ro->getItem(),
                            'amount' => $ro->getAmount(),
                            'date' => $ro->getDate(),
                        ])
                        ->values()
                        ->all(),
                ], JSON_THROW_ON_ERROR))
            );
    }
}
