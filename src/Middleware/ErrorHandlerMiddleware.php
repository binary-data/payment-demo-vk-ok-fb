<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->responseFactory
                ->createResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->streamFactory->createStream(json_encode([
                    'exception' => [
                        'text' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'code' => $e->getCode(),
                    ],
                ], JSON_THROW_ON_ERROR)));
        }
    }
}
