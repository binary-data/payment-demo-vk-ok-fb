<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class VkSignatureMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory,
        private string $appSecret
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestParams = $request->getParsedBody();
        $sig = $requestParams['sig'] ?? '';
        unset($requestParams['sig']);

        $paramString = collect($requestParams)
            ->keys()
            ->sort(fn (string $a, string $b) => $a <=> $b)
            ->map(fn (string $key) => $key . '=' . $requestParams[$key])
            ->implode('');

        if ($sig !== md5($paramString . $this->appSecret)) {
            return $this->responseFactory->createResponse()->withBody(
                $this->streamFactory->createStream(json_encode([
                    'error' => [
                        'error_code' => 10,
                        'error_msg' => 'signature mismatch',
                        'critical' => true,
                    ],
                ], JSON_THROW_ON_ERROR))
            );
        }

        return $handler->handle($request);
    }
}
