<?php

declare(strict_types=1);

namespace Binarydata\PaymentDemo\Middleware;

use Binarydata\PaymentDemo\Service\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

final class OkRequestLoggerMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(private LoggerFactory $loggerFactory) {
        $this->logger = $loggerFactory->create('ok');
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getQueryParams();

        $this->logger->info('OK request received', $params);

        return $handler->handle($request);
    }
}
