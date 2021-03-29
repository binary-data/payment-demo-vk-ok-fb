<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Middleware;

use Binarydata\PaymentDemo\Service\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

final class VkRequestLoggerMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(private LoggerFactory $loggerFactory) {
        $this->logger = $loggerFactory->create('vk');
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getParsedBody();

        $this->logger->info('VK request received', $params);

        return $handler->handle($request);
    }
}
